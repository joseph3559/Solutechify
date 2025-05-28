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

    public function publicEvents(Organization $organization)
    {
        $events = $organization->events()
            ->where('date', '>=', now())
            ->orderBy('date')
            ->get();
        return response()->json($events);
    }
} 