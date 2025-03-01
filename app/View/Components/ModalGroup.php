<?php

namespace App\View\Components;

use App\Models\Group;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalGroup extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?Group $group = null,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal-group');
    }
}
