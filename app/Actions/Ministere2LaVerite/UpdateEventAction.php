<?php


namespace App\Actions\Ministere2LaVerite;


use App\DTOs\events\EventDTO;
use App\Models\Assembly;
use App\Models\Event;

class UpdateEventAction
{
    public function execute(EventDTO $eventDTO, Event $event): void
    {
        $event->update([
            'title' => $eventDTO->title,
            'description' => $eventDTO->description,
            'from' => $eventDTO->from,
            'to' => $eventDTO->to,
            'status' => $eventDTO->status
        ]);

        $assemblies = Assembly::whereIn('id', $eventDTO->assemblies)->get();

        $event->assemblies()->detach();
        $event->assemblies()->attach($assemblies);

        \DB::commit();
    }
}
