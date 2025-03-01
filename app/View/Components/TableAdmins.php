<?php

namespace App\View\Components;

use App\Models\Roles;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableAdmins extends Component
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
        $data = User::whereHas('roles', fn($q) => $q->whereIn('roles.name', Roles::availableAdminsRoles()))
            ->orderBy('name', 'ASC')
            ->filter(request(['search']))
            ->paginate(10, ['*'], "adminsPage")
            ->withQueryString();

        return view('components.table-admins', compact('data'));
    }
}
