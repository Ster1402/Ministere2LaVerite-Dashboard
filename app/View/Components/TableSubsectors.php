<?php

namespace App\View\Components;

use App\Models\Sector;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableSubsectors extends Component
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
        $data = Sector::whereHas('master', function ($query) {
            $query->where('id', '>', 0);
        })
            ->filter(request(['search']))
            ->paginate(10, ['*'], 'sectorsPage')
            ->withQueryString();

        return view('components.table-subsectors', compact('data'));
    }
}
