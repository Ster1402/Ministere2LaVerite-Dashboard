<?php

namespace App\Http\Requests;

use App\DTOs\messages\MessageDTO;
use App\Models\Message;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Message::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string|min:3',
            'content' => 'required|string|min:3',
            'receiver' => 'integer',
            'assemblies' => 'array',
            'category' => 'string|min:3',
            'replyTo' => 'integer',
//            'sendToAssembly' => 'required|bool'
//            'tags' => 'string',
//            'picture' => 'file|image',
        ];
    }

    public function toDTO(): MessageDTO
    {
        $uri = '';

        if ($this->hasFile('picture')) {
            $uploadedFile = $this->file('picture');
            $filepath = $uploadedFile->store('messages/images', 'public');
            $uri = asset('storage/' . $filepath);
        }

        return new MessageDTO(
            subject: $this->input('subject'),
            content: $this->input('content'),
            senderId: \Auth::id(),
            receiverId: $this->input('sendToAssembly') ? null : $this->input('receiver'),
            assembliesId: $this->input('sendToAssembly') ? $this->input('assemblies') : [],
            messageId: $this->input('replyTo'),
            category: $this->input('category'),
            picturePath: $uri,
            tags: $this->input('tags') ?? '',
            received: true,
            seen: false,
        );
    }
}
