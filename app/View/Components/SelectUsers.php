<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SelectUsers extends Component
{
    /**
     * Create a new component instance.
     * @param Collection|array $users
     */
    public function __construct(
        public Collection|array $users = [],
    )
    {
        if (empty($users)) {
            $this->users = User::orderBy('name')->get();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-users', ['users' => $this->users]);
    }
}
