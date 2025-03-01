<?php

namespace App\Http\Requests;

use App\DTOs\commons\AssignRolesToUserDTO;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.update', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'admin' => ['required', 'exists:users,id'],
            'rolesNames' => ['required', 'array'],
        ];
    }

    public function toDTO(): AssignRolesToUserDTO
    {
        $user = User::findOrFail($this->admin);

        return new AssignRolesToUserDTO(
            user: $user,
            rolesNames: $this->rolesNames
        );
    }
}
