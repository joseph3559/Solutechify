<?php

namespace App\Repositories\Eloquent;

use App\Models\Attendee;
use App\Models\Event;
use App\Repositories\Contracts\AttendeeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AttendeeRepository implements AttendeeRepositoryInterface
{
    public function all(): Collection
    {
        return Attendee::all();
    }

    public function find(int $id): ?Attendee
    {
        return Attendee::find($id);
    }

    public function create(array $data): Attendee
    {
        return Attendee::create($data);
    }

    public function update(Attendee $attendee, array $data): bool
    {
        return $attendee->update($data);
    }

    public function delete(Attendee $attendee): bool
    {
        return $attendee->delete();
    }

    public function getEventAttendees(Event $event): Collection
    {
        return $event->attendees()->orderBy('created_at', 'desc')->get();
    }

    public function updateStatus(Attendee $attendee, string $status): bool
    {
        return $attendee->update(['status' => $status]);
    }
} 