<?php

namespace App\Repositories\Contracts;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

interface AttendeeRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Attendee;
    public function create(array $data): Attendee;
    public function update(Attendee $attendee, array $data): bool;
    public function delete(Attendee $attendee): bool;
    public function getEventAttendees(Event $event): Collection;
    public function updateStatus(Attendee $attendee, string $status): bool;
} 