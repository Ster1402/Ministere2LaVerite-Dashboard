<?php

namespace App\Http\Requests;

use App\DTOs\groups\GroupDTO;
use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', Group::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
        ];
    }

    public function toDTO(): GroupDTO
    {
        return new GroupDTO(
            name: $this->input('name'),
            description: $this->input('description'),
        );
    }
}
