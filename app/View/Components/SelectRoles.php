<?php

namespace App\View\Components;

use App\Models\Roles;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SelectRoles extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $roles = null,
        public ?Collection $defaults = null,
    )
    {
        $this->roles = Roles::orderBy('name')->get(['id', 'name', 'displayName']);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $roles = $this->roles;
        $defaults = $this->defaults;
        return view('components.select-roles', compact('roles', 'defaults'));
    }
}
