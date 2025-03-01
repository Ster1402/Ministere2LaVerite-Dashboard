<?php

namespace App\View\Components;

use App\Models\Media;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableDocuments extends Component
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
        $data = Media::orderBy('name', 'ASC')
            ->filter(request(['search', 'type']))
            ->paginate(10, ['*'], "mediasPage")
            ->withQueryString();

        return view('components.table-documents');
    }
}
