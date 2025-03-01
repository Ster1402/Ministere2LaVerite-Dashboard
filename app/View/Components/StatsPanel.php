<?php

namespace App\View\Components;

use App\View\DTOs\StatItem;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatsPanel extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title,
        public string $slug,
        public array $statItems,
        public int $total = 0,
    )
    {
        $this->total = $this->total ?: array_reduce($statItems, function (int $carry, StatItem $item) {
            return $carry + $item->count;
        }, 0);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.stats-panel');
    }
}
