<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Attendee;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Get user's dashboard data
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get user's event registrations with event details
        $registrations = Attendee::with(['event.organization'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Categorize events
        $now = Carbon::now();
        
        $upcoming = $registrations->filter(function ($registration) use ($now) {
            return $registration->status === 'registered' && 
                   Carbon::parse($registration->event->date) > $now;
        })->values();

        $past = $registrations->filter(function ($registration) use ($now) {
            return $registration->status === 'registered' && 
                   Carbon::parse($registration->event->date) <= $now;
        })->values();

        $cancelled = $registrations->filter(function ($registration) {
            return $registration->status === 'cancelled';
        })->values();

        // Get statistics
        $stats = [
            'total_events' => $registrations->where('status', 'registered')->count(),
            'upcoming_events' => $upcoming->count(),
            'past_events' => $past->count(),
            'cancelled_events' => $cancelled->count(),
        ];

        return response()->json([
            'user' => $user,
            'stats' => $stats,
            'events' => [
                'upcoming' => $upcoming,
                'past' => $past,
                'cancelled' => $cancelled,
                'all' => $registrations
            ]
        ]);
    }

    /**
     * Get user's registered events
     */
    public function events(Request $request)
    {
        $user = auth()->user();
        $type = $request->get('type', 'all'); // all, upcoming, past, cancelled
        
        $query = Attendee::with(['event.organization'])
            ->where('user_id', $user->id);

        $now = Carbon::now();
        
        switch ($type) {
            case 'upcoming':
                $query->where('status', 'registered')
                      ->whereHas('event', function ($q) use ($now) {
                          $q->where('date', '>', $now);
                      });
                break;
            case 'past':
                $query->where('status', 'registered')
                      ->whereHas('event', function ($q) use ($now) {
                          $q->where('date', '<=', $now);
                      });
                break;
            case 'cancelled':
                $query->where('status', 'cancelled');
                break;
        }

        $events = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return response()->json($events);
    }

    /**
     * Get user's calendar events
     */
    public function calendar(Request $request)
    {
        $user = auth()->user();
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $events = Attendee::with(['event.organization'])
            ->where('user_id', $user->id)
            ->where('status', 'registered')
            ->whereHas('event', function ($q) use ($year, $month) {
                $q->whereYear('date', $year)
                  ->whereMonth('date', $month);
            })
            ->get()
            ->map(function ($attendee) {
                return [
                    'id' => $attendee->event->id,
                    'title' => $attendee->event->title,
                    'date' => $attendee->event->date,
                    'venue' => $attendee->event->venue,
                    'organization' => $attendee->event->organization->name,
                    'registration_id' => $attendee->id,
                    'status' => $attendee->status
                ];
            });

        return response()->json($events);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Check current password if trying to change password
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || 
                !Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Current password is incorrect'], 422);
            }
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Remove password fields if not changing password
        unset($validated['current_password']);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh()
        ]);
    }

    /**
     * Cancel event registration
     */
    public function cancelRegistration(Request $request, $registrationId)
    {
        $user = auth()->user();
        
        $registration = Attendee::where('id', $registrationId)
            ->where('user_id', $user->id)
            ->where('status', 'registered')
            ->first();

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        // Check if event hasn't started yet
        $event = $registration->event;
        if (Carbon::parse($event->date) <= Carbon::now()) {
            return response()->json(['message' => 'Cannot cancel registration for past events'], 422);
        }

        $registration->update(['status' => 'cancelled']);
        $event->decrement('current_attendees');

        return response()->json(['message' => 'Registration cancelled successfully']);
    }

    /**
     * Get user profile
     */
    public function profile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get admin dashboard data
     */
    public function adminDashboard()
    {
        $user = auth()->user();
        
        // Check if user is admin
        if (!in_array($user->role, ['admin', 'super_admin', 'organizer'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        // Get organization-specific data for admins/organizers
        $organizationId = $user->role === 'super_admin' ? null : $user->organization_id;
        
        // Get events (all for super_admin, organization-specific for others)
        $eventsQuery = Event::with(['organization', 'attendees']);
        if ($organizationId) {
            $eventsQuery->where('organization_id', $organizationId);
        }
        $events = $eventsQuery->orderBy('created_at', 'desc')->get();
        
        // Get organizations (all for super_admin, own for others)
        $organizationsQuery = Organization::withCount('events');
        if ($organizationId) {
            $organizationsQuery->where('id', $organizationId);
        }
        $organizations = $organizationsQuery->orderBy('created_at', 'desc')->get();
        
        // Get attendees with event details
        $attendeesQuery = Attendee::with(['event.organization', 'user']);
        if ($organizationId) {
            $attendeesQuery->whereHas('event', function($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            });
        }
        $attendees = $attendeesQuery->orderBy('created_at', 'desc')->get();
        
        // Calculate statistics
        $now = Carbon::now();
        $upcomingEvents = $events->filter(function ($event) use ($now) {
            return Carbon::parse($event->date) > $now && $event->is_active;
        });
        
        $stats = [
            'total_events' => $events->count(),
            'upcoming_events' => $upcomingEvents->count(),
            'total_attendees' => $attendees->where('status', 'registered')->count(),
            'total_organizations' => $organizations->count(),
            'total_revenue' => $attendees->where('status', 'registered')->sum(function($attendee) {
                return $attendee->event->price;
            })
        ];
        
        return response()->json([
            'user' => $user,
            'stats' => $stats,
            'events' => $events,
            'organizations' => $organizations,
            'attendees' => $attendees
        ]);
    }
}
