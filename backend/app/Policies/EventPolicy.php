<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Organization;
use App\Models\User;

class EventPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any events.
     */
    public function viewAny(User $user, Organization $organization): bool
    {
        return $user->isAdmin() && $user->organization_id === $organization->id;
    }

    /**
     * Determine whether the user can view the event.
     */
    public function view(User $user, Event $event, Organization $organization): bool
    {
        return $user->isAdmin() && 
               $user->organization_id === $organization->id && 
               $event->organization_id === $organization->id;
    }

    /**
     * Determine whether the user can create events.
     */
    public function create(User $user, Organization $organization): bool
    {
        return $user->isAdmin() && $user->organization_id === $organization->id;
    }

    /**
     * Determine whether the user can update the event.
     */
    public function update(User $user, Event $event, Organization $organization): bool
    {
        return $user->isAdmin() && 
               $user->organization_id === $organization->id && 
               $event->organization_id === $organization->id;
    }

    /**
     * Determine whether the user can delete the event.
     */
    public function delete(User $user, Event $event, Organization $organization): bool
    {
        return $user->isAdmin() && 
               $user->organization_id === $organization->id && 
               $event->organization_id === $organization->id;
    }

    /**
     * Determine whether the user can view attendees.
     */
    public function viewAttendees(User $user, Event $event, Organization $organization): bool
    {
        return $user->isAdmin() && 
               $user->organization_id === $organization->id && 
               $event->organization_id === $organization->id;
    }

    /**
     * Determine whether the user can delete attendees.
     */
    public function deleteAttendee(User $user, Event $event, Organization $organization): bool
    {
        return $user->isAdmin() && 
               $user->organization_id === $organization->id && 
               $event->organization_id === $organization->id;
    }
}
