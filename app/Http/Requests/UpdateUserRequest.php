<?php

namespace App\Http\Requests;

use App\DTOs\users\BaptismDTO;
use App\DTOs\users\UpdateUserDTO;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:4',
            'gender' => 'required|string',
            'password' => 'confirmed',
            'profession' => 'required|string',
            'baptism_type' => 'array',
            'baptism_nominalMaker' => 'required|string',
            'baptism_ministerialLevel' => 'required|string'
        ];
    }

    public function toDTO(): UpdateUserDTO
    {
        $user = new UpdateUserDTO(
            name: $this->input('name'),
            email: $this->input('email'),
            gender: $this->input('gender') ?? 'unknown',
            password: $this->input('password'),
            profession: $this->input('profession'),
            profession_details: $this->input('profession_details'),
            surname: $this->input('surname'),
            phoneNumber: $this->input('phoneNumber'),
            dateOfBirth: $this->input('dateOfBirth'),
            residence: $this->input('residence'),
            antecedent: $this->input('antecedent'),
            isActive: (bool)$this->input('isActive'),
            isDisciplined: (bool)$this->input('isDisciplined'),
            arrivalDate: (string)$this->input('arrivalDate'),
            maritalStatus: $this->input('maritalStatus'),
            numberOfChildren: $this->input('numberOfChildren'),
            sterileWoman: (bool)$this->input('sterileWoman'),
            seriousIllnesses: $this->input('seriousIllnesses'),
            comment: $this->input('comment'),
            assembly: $this->input('assembly'),
        );

        $user->baptism = new BaptismDTO(
            type: collect($this->input('baptism_type'))->join('_'),
            nominalMaker: $this->input('baptism_nominalMaker'),
            hasHolySpirit: (bool)$this->input('baptism_hasHolySpirit'),
            ministerialLevel: $this->input('baptism_ministerialLevel'),
            spiritualLevel: (int)$this->input('baptism_spiritualLevel'),
            dateWater: $this->input('baptism_date_water'),
            dateHolySpirit: $this->input('baptism_date_holy_spirit'),
            dateLatest: $this->input('baptism_date_latest'),
        );
        return $user;
    }

}
