<?php

namespace App\Actions\Fortify;

use App\Models\Baptism;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Comprehensive validation for church management system
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'gender' => ['required', 'in:male,female,unknown'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',

            // Church-specific fields
            'profession' => ['required', 'string'],
            'profession_details' => ['nullable', 'string'],
            'dateOfBirth' => ['nullable', 'date'],
            'arrivalDate' => ['nullable', 'date'],
            'residence' => ['nullable', 'string'],
            'phoneNumber' => ['nullable', 'string'],

            // Baptism details
            'baptism_type' => ['nullable', 'array'],
            'baptism_nominalMaker' => ['nullable', 'string'],
            'baptism_ministerialLevel' => ['nullable', 'string'],
        ])->validate();

        return \DB::transaction(function () use ($input) {
            // Create user
            $user = User::create([
                'name' => $input['name'],
                'surname' => $input['surname'] ?? null,
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'gender' => $input['gender'],
                'profession' => $input['profession'],
                'profession_details' => $input['profession_details'] ?? null,
                'dateOfBirth' => $input['dateOfBirth'] ?? null,
                'arrivalDate' => $input['arrivalDate'] ?? null,
                'residence' => $input['residence'] ?? null,
                'phoneNumber' => $input['phoneNumber'] ?? null,
                'isActive' => true,
                'isDisciplined' => true,
            ]);

            // Create baptism record
            $baptismType = isset($input['baptism_type'])
                ? implode('_', $input['baptism_type'] ?? null)
                : 'none';

            Baptism::create([
                'user_id' => $user->id,
                'type' => $baptismType,
                'nominalMaker' => $input['baptism_nominalMaker'] ?? null,
                'ministerialLevel' => $input['baptism_ministerialLevel'] ?? null,
                'hasHolySpirit' => false,
                'spiritualLevel' => 0,
            ]);

            // Assign default end-user role
            $endUserRole = Roles::firstWhere('name', Roles::$END_USER);
            if ($endUserRole) {
                $user->roles()->attach($endUserRole);
            }

            return $user;
        });
    }
}
