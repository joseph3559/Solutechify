<?php

namespace App\Services;

use App\Models\Attendee;
use App\Models\Event;
use App\Repositories\Contracts\AttendeeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AttendeeService
{
    public function __construct(
        private AttendeeRepositoryInterface $attendeeRepository,
        private EventService $eventService
    ) {}

    public function getAllAttendees(): Collection
    {
        return $this->attendeeRepository->all();
    }

    public function getAttendee(int $id): ?Attendee
    {
        return $this->attendeeRepository->find($id);
    }

    public function createAttendee(array $data): Attendee
    {
        return DB::transaction(function () use ($data) {
            $event = Event::findOrFail($data['event_id']);
            
            if (!$this->eventService->hasAvailableSpots($event)) {
                throw new \RuntimeException('Event is fully booked.');
            }

            $attendee = $this->attendeeRepository->create($data);
            $this->eventService->incrementAttendees($event);

            return $attendee;
        });
    }

    public function updateAttendee(Attendee $attendee, array $data): bool
    {
        return DB::transaction(function () use ($attendee, $data) {
            return $this->attendeeRepository->update($attendee, $data);
        });
    }

    public function deleteAttendee(Attendee $attendee): bool
    {
        return DB::transaction(function () use ($attendee) {
            $event = $attendee->event;
            $deleted = $this->attendeeRepository->delete($attendee);
            
            if ($deleted) {
                $this->eventService->decrementAttendees($event);
            }

            return $deleted;
        });
    }

    public function getEventAttendees(Event $event): Collection
    {
        return $this->attendeeRepository->getEventAttendees($event);
    }

    public function updateStatus(Attendee $attendee, string $status): bool
    {
        if (!in_array($status, ['registered', 'confirmed', 'cancelled'])) {
            throw new \InvalidArgumentException('Invalid status provided.');
        }

        return DB::transaction(function () use ($attendee, $status) {
            $oldStatus = $attendee->status;
            $updated = $this->attendeeRepository->updateStatus($attendee, $status);

            if ($updated && $oldStatus !== 'cancelled' && $status === 'cancelled') {
                $this->eventService->decrementAttendees($attendee->event);
            } elseif ($updated && $oldStatus === 'cancelled' && $status !== 'cancelled') {
                $this->eventService->incrementAttendees($attendee->event);
            }

            return $updated;
        });
    }
} 