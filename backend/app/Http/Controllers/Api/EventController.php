<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Organization $organization)
    {
        $this->authorize('viewAny', [Event::class, $organization]);
        $events = $organization->events()->with('attendees')->get();
        return response()->json($events);
    }

    public function store(Request $request, Organization $organization)
    {
        $this->authorize('create', [Event::class, $organization]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'required|string',
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'max_attendees' => 'required|integer|min:1',
        ]);

        $event = $organization->events()->create($validated);
        return response()->json($event, 201);
    }

    public function show(Organization $organization, Event $event)
    {
        $this->authorize('view', [$event, $organization]);
        return response()->json($event->load('attendees'));
    }

    public function update(Request $request, Organization $organization, Event $event)
    {
        $this->authorize('update', [$event, $organization]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'required|string',
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'max_attendees' => 'required|integer|min:1',
        ]);

        $event->update($validated);
        return response()->json($event);
    }

    public function destroy(Organization $organization, Event $event)
    {
        $this->authorize('delete', [$event, $organization]);
        $event->delete();
        return response()->json(null, 204);
    }

    /**
     * Display all active events for public viewing
     */
    public function publicIndex()
    {
        $events = Event::with('organization')
            ->where('is_active', true)
            ->orderBy('date')
            ->get();
        return response()->json($events);
    }

    /**
     * Display a specific event by ID for public viewing
     */
    public function publicShowById(Event $event)
    {
        // Only show active events
        if (!$event->is_active) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        return response()->json($event->load('organization'));
    }

    public function publicEvents(Organization $organization)
    {
        $events = Event::where('organization_id', $organization->id)
            ->where('is_active', true)
            ->orderBy('date')
            ->get();
        return response()->json($events);
    }

    /**
     * Display the specified event for public viewing
     */
    public function publicShow(Organization $organization, Event $event)
    {
        // Ensure the event belongs to the organization and is active
        if ($event->organization_id !== $organization->id || !$event->is_active) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        return response()->json($event);
    }
} 