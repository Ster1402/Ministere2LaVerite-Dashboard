<?php

namespace App\View\Components;

use App\Models\Borrowed;
use App\Models\Resource;
use App\View\DTOs\StatItem;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatsResourcesPanel extends Component
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
        $resources = Resource::all();
        $borrowed = Borrowed::all();

        $numberOfResources = $resources->sum(fn ($resource) => $resource->quantity);
        $numberOfResourcesBorrowed = $borrowed->sum(fn ($pivot) => $pivot->quantity);

        $stats = [
            new StatItem(
                name: 'Disponible',
                count: $numberOfResources - $numberOfResourcesBorrowed
            ),
            new StatItem(
                name: 'Emprunté',
                count: $numberOfResourcesBorrowed
            ),
            new StatItem(
                name: 'Indisponible',
                count: 0
            )
        ];

        return view('components.stats-resources-panel', compact('stats'));
    }
}
