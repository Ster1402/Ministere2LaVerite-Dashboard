<?php

namespace App\DTOs\assembly;

use Spatie\LaravelData\Data;

class AssemblyDTO extends Data
{
    public function __construct(
        public string $name,
        public int $sectorId,
        public ?string $description,
    ){}
}
