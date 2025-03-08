<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\commons\AssignRolesToUserDTO;
use App\Models\Roles;
use Illuminate\Support\Facades\DB;

class AssignRolesToUserAction
{
    public function execute(AssignRolesToUserDTO $updateAdminDTO): void
    {
        // Use a database transaction to ensure data integrity
        DB::transaction(function () use ($updateAdminDTO) {
            // 1. Remove existing roles
            $updateAdminDTO->user->roles()->detach();

            // 2. Find the roles to be assigned
            $roles = Roles::whereIn('name', $updateAdminDTO->rolesNames)->get();

            // 3. Attach new roles with explicit timestamps
            $updateAdminDTO->user->roles()->attach(
                $roles->pluck('id'),
                [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        });

        // Optional: Flash a success message
        session()->flash('success', 'Roles updated successfully');
    }
}
