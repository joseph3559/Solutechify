<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function __construct(
        private EventRepositoryInterface $eventRepository
    ) {}

    public function getAllEvents(): Collection
    {
        return $this->eventRepository->all();
    }

    public function getEvent(int $id): ?Event
    {
        return $this->eventRepository->find($id);
    }

    public function createEvent(array $data): Event
    {
        return DB::transaction(function () use ($data) {
            return $this->eventRepository->create($data);
        });
    }

    public function updateEvent(Event $event, array $data): bool
    {
        return DB::transaction(function () use ($event, $data) {
            return $this->eventRepository->update($event, $data);
        });
    }

    public function deleteEvent(Event $event): bool
    {
        return DB::transaction(function () use ($event) {
            return $this->eventRepository->delete($event);
        });
    }

    public function getUpcomingEvents(): Collection
    {
        return $this->eventRepository->getUpcomingEvents();
    }

    public function getActiveEvents(): Collection
    {
        return $this->eventRepository->getActiveEvents();
    }

    public function hasAvailableSpots(Event $event): bool
    {
        return $event->hasAvailableSpots();
    }

    public function incrementAttendees(Event $event): bool
    {
        if (!$this->hasAvailableSpots($event)) {
            throw new \RuntimeException('No available spots for this event.');
        }

        return DB::transaction(function () use ($event) {
            return $this->eventRepository->incrementAttendees($event);
        });
    }

    public function decrementAttendees(Event $event): bool
    {
        return DB::transaction(function () use ($event) {
            return $this->eventRepository->decrementAttendees($event);
        });
    }
} 