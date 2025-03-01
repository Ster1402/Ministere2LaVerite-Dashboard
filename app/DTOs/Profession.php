<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class Profession extends Data
{
    public function __construct(
        public string $name,
        public string $displayName,
    )
    {
    }
}
