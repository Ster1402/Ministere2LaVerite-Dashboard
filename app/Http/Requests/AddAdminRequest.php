<?php

namespace App\Http\Requests;

use App\DTOs\commons\AssignRolesToUserDTO;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use JetBrains\PhpStorm\ArrayShape;

class AddAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['user' => "string[]", 'rolesNames' => "string[]"])]
    public function rules(): array
    {
        return [
            'user' => ['required', 'exists:users,id'],
            'rolesNames' => ['required', 'array'],
        ];
    }

    public function toDTO(): AssignRolesToUserDTO
    {
        $user = User::findOrFail($this->user);

        return new AssignRolesToUserDTO(
            user: $user,
            rolesNames: $this->rolesNames
        );
    }
}
