<?php

namespace App\Http\Requests;

use App\DTOs\assembly\AssemblyDTO;
use App\Models\Assembly;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateAssemblyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', Assembly::class);
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
            'sector' => 'required|integer',
//            'description' => 'string',
        ];
    }

    public function toDTO(): AssemblyDTO
    {
        return new AssemblyDTO(
            name: $this->input('name'),
            sectorId: $this->input('sector'),
            description: $this->input('description')
        );
    }
}
