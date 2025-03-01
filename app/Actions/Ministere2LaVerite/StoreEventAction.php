<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\events\EventDTO;
use App\Models\Assembly;
use App\Models\Event;

class StoreEventAction
{
    public function execute(EventDTO $eventDTO): void
    {
        $event = Event::create([
            'title' => $eventDTO->title,
            'description' => $eventDTO->description,
            'from' => $eventDTO->from,
            'to' => $eventDTO->to,
            'status' => $eventDTO->status
        ]);

        $assemblies = Assembly::whereIn('id', $eventDTO->assemblies)->get();
        $event->assemblies()->attach($assemblies);
    }
}
