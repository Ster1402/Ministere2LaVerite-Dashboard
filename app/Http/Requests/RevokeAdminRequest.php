<?php

namespace App\Http\Requests;

use App\DTOs\admins\RevokeAdminDTO;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class RevokeAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.revoke');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'admin' => "required|exists:users,id",
        ];
    }

    public function toDTO(): RevokeAdminDTO
    {
        $user = User::find($this->admin);

        return new RevokeAdminDTO(
            user: $user
        );
    }
}
