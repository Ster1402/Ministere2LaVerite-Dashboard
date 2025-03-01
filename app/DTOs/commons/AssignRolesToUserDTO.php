<?php


namespace App\DTOs\commons;

use App\Models\User;
use Spatie\LaravelData\Data;

class AssignRolesToUserDTO extends Data
{
    public function __construct(
        public User $user,
        public array $rolesNames
    )
    {
    }
}
