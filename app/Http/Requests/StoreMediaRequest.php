<?php

namespace App\Http\Requests;

use App\DTOs\medias\MediasDTO;
use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreMediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Media::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'receiver' => 'integer',
            'assemblies' => 'array',
            'medias' => 'array',
        ];
    }

    public function toDTO(): MediasDTO
    {
        return new MediasDTO(
            files: (array)$this->file('medias'),
            comment: $this->input('comment') ?? "RAS",
            sendToAssemblies: (bool)$this->input('sendToAssemblies'),
            userId: $this->input('receiver'),
            assemblies: (array)$this->input('assemblies')
        );
    }
}
