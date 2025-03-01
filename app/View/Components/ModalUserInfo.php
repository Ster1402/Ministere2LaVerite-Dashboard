<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalUserInfo extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?User $user = null
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
//        ddd($this->user);
        return view('components.modal-user-info', ['user' => $this->user]);
    }
}
