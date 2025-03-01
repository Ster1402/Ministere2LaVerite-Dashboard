<?php


namespace App\DTOs\groups;

use Spatie\LaravelData\Data;

class GroupDTO extends Data
{
    public function __construct(
        public string  $name,
        public ?string $description = null,
    )
    {
    }
}
