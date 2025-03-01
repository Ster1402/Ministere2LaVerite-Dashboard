<?php

namespace App\View\Components;

use App\Models\Resource;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableResources extends Component
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
        $data = Resource::orderBy('name', 'ASC')
            ->filter(request(['search']))
            ->paginate(10, ['*'], 'resourcesPage')
            ->withQueryString();

        return view('components.table-resources', compact('data'));
    }
}
