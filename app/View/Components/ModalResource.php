<?php

namespace App\View\Components;

use App\Models\Group;
use App\Models\Resource;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ModalResource extends Component
{
    public Collection $groups;
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?Resource $resource = null,
    )
    {
        $this->groups = Group::orderBy('name')->get(['id', 'name']);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        return view('components.modal-resource');
    }
}
