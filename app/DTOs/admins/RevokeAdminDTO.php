<?php

namespace App\DTOs\admins;

use App\Models\User;
use Spatie\LaravelData\Data;

class RevokeAdminDTO extends Data
{
    public function __construct(
        public User $user
    )
    {
    }
}
