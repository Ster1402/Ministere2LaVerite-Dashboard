<?php

namespace App\View\Components;

use App\Models\Event;
use App\View\DTOs\StatItem;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatsEventsPanel extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $events = Event::all();

        $numberOfEventsWaited = $events
            ->filter(fn ($event) => $event->from != null && $event->to?->gt(Carbon::now()))
            ->count();

        $numberOfEventsCompleted = $events
            ->filter(fn ($event) => $event->from != null && $event->to?->lt(Carbon::now()))
            ->count();

        $numberOfEventsReported = $events
            ->filter(fn ($event) => $event->from == null) // A date null means that the event is reported to an unknown date
            ->count();

        $stats = [
            new StatItem(
                name: 'En Attente',
                count: $numberOfEventsWaited
            ),
            new StatItem(
                name: 'Complété(s)',
                count: $numberOfEventsCompleted
            ),
            new StatItem(
                name: 'Réporté(s)',
                count: $numberOfEventsReported
            )
        ];

        return view('components.stats-events-panel', compact('stats'));
    }
}
