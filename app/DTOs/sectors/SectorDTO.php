<?php

namespace App\DTOs\sectors;

use Spatie\LaravelData\Data;

class SectorDTO extends Data
{
    public function __construct(
        public string $name,
        public ?int $masterId,
        public ?string $description,
    ){}
}
