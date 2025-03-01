<?php

namespace App\View\Components;

use App\Models\Event;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableEvents extends Component
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
        $data = Event::orderBy('from', 'desc')
            ->filter(request(['search', 'category', 'from', 'to']))
            ->paginate(10, ['*'], 'eventsPage')
            ->withQueryString();

        return view('components.table-events', compact('data'));
    }
}
