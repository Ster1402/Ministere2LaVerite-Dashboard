<?php

namespace App\DTOs\medias;

use Spatie\LaravelData\Data;

class MediasDTO extends Data
{
    public function __construct(
        public array   $files,
        public ?string $comment,
        public bool    $sendToAssemblies = false,
        public ?int    $userId = null,
        public array   $assemblies = [],
    )
    {
    }
}
