<?php

namespace App\Policies;

use App\Models\Donation;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DonationPolicy
{
    /**
     * Determine whether the user can delete the donation.
     *
     * @param User $user
     * @param Donation $donation
     * @return Response
     */
    public function delete(User $user, Donation $donation): Response
    {
        // Only admins or specific roles can delete donations
        if (
            $user->isAdmin()
        ) {
            return Response::allow();
        }

        return Response::deny('Vous n\'êtes pas autorisé à supprimer des dons.');
    }
}
