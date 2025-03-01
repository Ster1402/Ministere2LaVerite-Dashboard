<?php

namespace App\View\Components;

use App\Models\Roles;
use App\Models\User;
use App\View\DTOs\StatItem;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatsUsersPanel extends Component
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
        $stats = [];
        $total = User::count();
        $roles = Roles::withCount('users')->orderBy('created_at', 'desc')->get();

        foreach ($roles as $role) {
            $stats = [...$stats, new StatItem(
                name: $role->displayName,
                count: $role->users_count ?: 0
            )];
        }

        return view('components.stats-users-panel', compact('stats', 'total'));
    }
}
