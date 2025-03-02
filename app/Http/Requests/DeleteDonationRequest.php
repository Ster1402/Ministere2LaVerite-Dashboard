<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DeleteDonationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to delete the donation.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        // Only admin or specific roles can delete donations
        return Gate::allows('delete', $this->route('donation'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // No specific validation needed for deletion
        ];
    }
}
