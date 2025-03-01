<?php

namespace App\Http\Requests;

use App\DTOs\resources\ResourceDTO;
use App\Models\Resource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Resource::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'group_id' => 'required|integer',
            'quantity' => 'required|integer',
        ];
    }

    public function toDTO(): ResourceDTO
    {
        return new ResourceDTO(
            name: $this->input('name'),
            groupId: $this->input('group_id'),
            quantity: $this->input('quantity'),
            description: $this->input('description'),
        );
    }
}
