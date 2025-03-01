<?php


namespace App\DTOs;


use App\Models\User;
use Spatie\LaravelData\Data;

class AddAdminDTO extends Data
{
    public function __construct(
        public User $user,
        public array $rolesNames
    )
    {
    }
}
