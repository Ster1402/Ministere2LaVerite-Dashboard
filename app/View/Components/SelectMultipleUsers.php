<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SelectMultipleUsers extends Component
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
        $users = User::orderBy('name')->get(['id', 'name']);
        return view('components.select-multiple-users', compact('users'));
    }
}
