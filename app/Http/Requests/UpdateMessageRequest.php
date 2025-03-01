<?php

namespace App\Http\Requests;

use App\DTOs\messages\MessageDTO;
use App\Models\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string|min:3',
            'content' => 'required|string|min:3',
            'receiver' => 'integer',
            'assemblies' => 'array',
            'category' => 'string|min:3',
//            'tags' => 'string',
//            'picture' => 'file|image',
        ];
    }

    public function toDTO(): MessageDTO
    {
        $uri = null;

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
            category: $this->input('category'),
            picturePath: $uri,
            tags: $this->input('tags') ?? '',
            received: true,
            seen: false,
        );
    }

}
