<?php

namespace App\DTOs\messages;

use Spatie\LaravelData\Data;

class MessageDTO extends Data
{
    public function __construct(
        public string $subject,
        public string $content,
        public int    $senderId,
        public ?int    $receiverId = null,
        public array    $assembliesId = [],
        public ?int    $messageId = null,
        public ?string $category = null,
        public ?string $picturePath = null,
        public ?string $tags = null,
        public bool    $received = true,
        public bool    $seen = false,
    )
    {
    }
}
