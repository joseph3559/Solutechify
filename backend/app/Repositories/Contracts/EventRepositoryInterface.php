<?php

namespace App\Repositories\Contracts;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Event;
    public function create(array $data): Event;
    public function update(Event $event, array $data): bool;
    public function delete(Event $event): bool;
    public function getUpcomingEvents(): Collection;
    public function getActiveEvents(): Collection;
    public function incrementAttendees(Event $event): bool;
    public function decrementAttendees(Event $event): bool;
} 