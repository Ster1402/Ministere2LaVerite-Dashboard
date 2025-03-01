<?php

namespace App\View\Components;

use App\Models\Assembly;
use App\Models\Sector;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ModalAssembly extends Component
{
    public Collection $sectors;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?Assembly $assembly = null,
    )
    {
        $this->sectors = Sector::orderBy('name')->get(['id', 'name']);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal-assembly', [
            'assembly' => $this->assembly,
            'sectors' => $this->sectors,
        ]);
    }
}
