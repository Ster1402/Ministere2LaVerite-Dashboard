<?php


namespace App\Actions\Ministere2LaVerite;


use App\DTOs\commons\AssignRolesToUserDTO;
use App\Models\Roles;

class AssignRolesToUserAction
{
    public function execute(AssignRolesToUserDTO $updateAdminDTO): void
    {
        $updateAdminDTO->user->roles()->detach();
        $roles = Roles::whereIn('name', $updateAdminDTO->rolesNames)->get();
        $updateAdminDTO->user->roles()->attach($roles);
    }
}
