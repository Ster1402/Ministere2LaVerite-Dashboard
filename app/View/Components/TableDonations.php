<?php

namespace App\View\Components;

use App\Models\Transaction;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableDonations extends Component
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
        $donations = Transaction::orderBy('created_at', 'desc')
            ->paginate(5, ['*'], "donationsPage")
            ->withQueryString();

        return view('components.table-donations', compact('donations'));
    }
}
