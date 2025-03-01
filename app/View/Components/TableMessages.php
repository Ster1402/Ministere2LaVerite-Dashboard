<?php

namespace App\View\Components;

use App\Models\Message;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class TableMessages extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?LengthAwarePaginator $data
    )
    {
        if (is_null($this->data)) {
            $this->data = Message::orderBy('subject', 'asc')
                ->where('receiverId', auth()->id())
                ->filter(request(['search', 'category', 'assembly', 'author']))
                ->paginate(15, ['*'], 'messagesPage')
                ->withQueryString();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-messages', ['data' => $this->data]);
    }
}
