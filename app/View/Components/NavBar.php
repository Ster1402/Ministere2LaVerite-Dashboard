<?php

namespace App\View\Components;

use Auth;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NavBar extends Component
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
        $messages = Auth::user()?->messagesReceived;
        return view('components.nav-bar', compact('messages'));
    }
}
