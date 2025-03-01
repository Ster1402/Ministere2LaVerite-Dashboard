<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\messages\MessageDTO;
use App\Models\Assembly;
use App\Models\Message;

class UpdateMessageAction
{
    public function execute(MessageDTO $messageDTO, Message $message): void
    {
        $message->update([
            'subject' => $messageDTO->subject,
            'seen' => $messageDTO->seen,
            'tags' => $messageDTO->tags,
            'category' => $messageDTO->category,
            'content' => $messageDTO->content,
            'picture_path' => $messageDTO->picturePath ?? $message->picture_path,
            'received' => $messageDTO->received,
            'receiverId' => $messageDTO->receiverId,
            'senderId' => $messageDTO->senderId,
        ]);

        \DB::commit();

        $message->assemblies()->detach();

        foreach ($messageDTO->assembliesId as $assemblyId) {
            $message->update(['receiverId' => null]);
            $assembly = Assembly::firstWhere('id', $assemblyId);
            $message->assemblies()->attach($assembly);
        }

        \DB::commit();

        session()->flash('success', 'Message updated successfully');
    }
}
