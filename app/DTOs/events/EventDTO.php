<?php


namespace App\DTOs\events;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class EventDTO extends Data
{
    public ?Collection $assemblies;
    public string $status;

    public function __construct(
        public string $title,
        public ?string $description = null,
        public ?string $from = null,
        public ?string $to = null,
        public ?int $user = null,
    ) {
        if ($from != null && Carbon::now()->gt(Carbon::parse($to))) {
            $this->status = 'ended';
        } elseif ($from != null && Carbon::now()->lt(Carbon::parse($from))) {
            $this->status = 'unavailable';
        } else {
            $this->status = 'ongoing';
        }
    }
}
