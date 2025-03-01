<?php

namespace App\View\Components;

use App\Models\Event;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalEventInfo extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?Event $event = null,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal-event-info', ['event' => $this->event]);
    }
}
