<?php
namespace App\View\DTOs;

class StatItem
{
    public function __construct(
        public string $name,
        public int $count,
    )
    {
    }
}
