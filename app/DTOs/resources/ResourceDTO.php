<?php

namespace App\DTOs\resources;

use Spatie\LaravelData\Data;

class ResourceDTO extends Data
{
    public function __construct(
        public string  $name,
        public int $groupId,
        public int $quantity = 0,
        public ?string $description = null,
    )
    {
    }
}
