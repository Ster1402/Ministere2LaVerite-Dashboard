<?php

namespace App\View\Components;

use App\Models\Roles;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SelectAdminsRoles extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?Collection $defaults = null,
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $roles = Roles::whereIn('name', Roles::availableAdminsRoles())
            ->orderBy('name')->get(['id', 'name', 'displayName']);
        $defaults = $this->defaults;
        return view('components.select-admins-roles', compact('roles', 'defaults'));
    }
}
