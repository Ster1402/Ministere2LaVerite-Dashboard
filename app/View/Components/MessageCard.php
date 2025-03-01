<?php

namespace App\View\Components;

use App\Models\Message;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MessageCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Message $message,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.message-card', [
            'id' => $this->message->id,
            'msg' => $this->message->subject,
            'sender' => $this->message->sender->name . ' ' . $this->message->sender->surname,
            'sendAt' => $this->message->created_at->diffForHumans(),
            'updatedAt' => $this->message->updated_at->diffForHumans(),
        ]);
    }
}
