<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        if ($user->isAdmin() || $user->roles->contains('name', Roles::$HUB_MANAGER)) {
            return Response::allow();
        } else {
            return Response::deny('You are not authorized to perform this action.');
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): Response
    {
        if ($user->isAdmin() || $user->roles->contains('name', Roles::$HUB_MANAGER)) {
            return Response::allow();
        } else {
            return Response::deny('You are not authorized to perform this action.');
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if ($user->isAdmin() || $user->roles->contains('name', Roles::$HUB_MANAGER)) {
            return Response::allow();
        } else {
            return Response::deny('You are not authorized to perform this action.');
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): Response
    {
        if ($user->isAdmin() || $user->roles->contains('name', Roles::$HUB_MANAGER)) {
            return Response::allow();
        } else {
            return Response::deny('You are not authorized to perform this action.');
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): Response
    {
        if ($user->isAdmin() || $user->roles->contains('name', Roles::$HUB_MANAGER)) {
            return Response::allow();
        } else {
            return Response::deny('You are not authorized to perform this action.');
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): Response
    {
        if ($user->isAdmin() || $user->roles->contains('name', Roles::$HUB_MANAGER)) {
            return Response::allow();
        } else {
            return Response::deny('You are not authorized to perform this action.');
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): Response
    {
        if ($user->isAdmin() || $user->roles->contains('name', Roles::$HUB_MANAGER)) {
            return Response::allow();
        } else {
            return Response::deny('You are not authorized to perform this action.');
        }
    }
}
