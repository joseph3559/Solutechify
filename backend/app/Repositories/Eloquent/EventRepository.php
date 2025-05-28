<?php

namespace App\Repositories\Eloquent;

use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function all(): Collection
    {
        return Event::all();
    }

    public function find(int $id): ?Event
    {
        return Event::find($id);
    }

    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function update(Event $event, array $data): bool
    {
        return $event->update($data);
    }

    public function delete(Event $event): bool
    {
        return $event->delete();
    }

    public function getUpcomingEvents(): Collection
    {
        return Event::where('event_date', '>', now())
            ->where('is_active', true)
            ->orderBy('event_date')
            ->get();
    }

    public function getActiveEvents(): Collection
    {
        return Event::where('is_active', true)
            ->orderBy('event_date')
            ->get();
    }

    public function incrementAttendees(Event $event): bool
    {
        return $event->increment('current_attendees');
    }

    public function decrementAttendees(Event $event): bool
    {
        return $event->decrement('current_attendees');
    }
} 