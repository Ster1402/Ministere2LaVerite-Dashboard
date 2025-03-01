<?php

namespace App\View\Components;

use App\Models\Assembly;
use App\Models\Sector;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableAssemblies extends Component
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
        $data = Assembly::orderBy('name', 'ASC')
            ->filter(request(['search']))
            ->paginate(10, ['*'], 'assembliesPage')
            ->withQueryString();

        return view('components.table-assemblies', compact('data'));
    }
}
