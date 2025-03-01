<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\messages\MessageDTO;
use App\Models\Assembly;
use App\Models\Message;

class StoreMessageAction
{
    public function execute(MessageDTO $messageDTO): void
    {
        $message = Message::create([
            'subject' => $messageDTO->subject,
            'seen' => $messageDTO->seen,
            'tags' => $messageDTO->tags,
            'category' => $messageDTO->category,
            'content' => $messageDTO->content,
            'picture_path' => $messageDTO->picturePath,
            'message_id' => $messageDTO->messageId,
            'received' => $messageDTO->received,
            'receiverId' => $messageDTO->receiverId,
            'senderId' => $messageDTO->senderId,
        ]);

        foreach ($messageDTO->assembliesId as $assemblyId) {
            $message->update(['receiverId' => null]);
            $assembly = Assembly::firstWhere('id', $assemblyId);
            $message->assemblies()->attach($assembly);
        }

        session()->flash('success', 'Message sent successfully');
    }
}
