<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, $model = null): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        } else {
            return Response::deny('You are not authorized to view this page');
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        } else {
            return Response::deny('You are not authorized to view this page');
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if ($user->isAdmin()) {
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
        if ($user->isAdmin()) {
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
        if ($user->isAdmin()) {
            return Response::allow();
        } else {
            return Response::deny('You are not authorized to perform this action.');
        }
    }

}
