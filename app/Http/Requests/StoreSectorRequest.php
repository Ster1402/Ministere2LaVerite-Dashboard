<?php

namespace App\Http\Requests;

use App\DTOs\sectors\SectorDTO;
use App\Models\Sector;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreSectorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Sector::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|unique:sectors,name',
        ];
    }

    public function toDTO(): SectorDTO
    {
        return new SectorDTO(
            name: $this->input('name'),
            masterId: $this->input('master_id'),
            description: $this->input('description'),
        );
    }
}
