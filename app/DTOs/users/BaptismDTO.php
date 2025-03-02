<?php

namespace App\DTOs\users;

use Spatie\LaravelData\Data;

class BaptismDTO extends Data
{
    public function __construct(
        public string $type,
        public string $nominalMaker,
        public bool $hasHolySpirit,
        public string $ministerialLevel,
        public int $spiritualLevel,
        public ?string $dateWater = null,
        public ?string $dateHolySpirit = null,
        public ?string $dateLatest = null,
    ) {}
}
