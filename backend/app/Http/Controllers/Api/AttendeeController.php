<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Attendee;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendeeController extends Controller
{
    public function index(Organization $organization, Event $event)
    {
        $this->authorize('viewAttendees', [$event, $organization]);
        return response()->json($event->attendees);
    }

    public function register(Request $request, Organization $organization, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
        ]);

        try {
            $result = DB::transaction(function () use ($event, $validated) {
                if ($event->current_attendees >= $event->max_attendees) {
                    throw new \Exception('Event is fully booked.');
                }

                $attendee = $event->attendees()->create($validated);
                $event->increment('current_attendees');

                return $attendee;
            });

            return response()->json([
                'message' => 'Registration successful',
                'data' => $result
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Organization $organization, Event $event, Attendee $attendee)
    {
        $this->authorize('deleteAttendee', [$event, $organization]);
        
        DB::transaction(function () use ($event, $attendee) {
            $attendee->delete();
            $event->decrement('current_attendees');
        });

        return response()->json(null, 204);
    }
} 