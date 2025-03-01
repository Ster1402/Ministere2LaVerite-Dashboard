<?php

namespace App\View\Components;

use App\Models\Assembly;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SelectAssembly extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?Collection $defaults = null
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data = Assembly::orderBy('name')->get(['id', 'name']);
        return view('components.select-assembly', compact('data'));
    }
}
