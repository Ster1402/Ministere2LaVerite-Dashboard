<?php

namespace App\Http\Controllers\Auth;

use App\Models\Baptism;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController
{
    /**
     * Validate user registration input
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'gender' => ['required', Rule::in(['male', 'female', 'unknown'])],

            // Church-specific fields
            'profession' => ['required', 'string'],
            'profession_details' => ['nullable', 'string'],
            'residence' => ['nullable', 'string', 'max:255'],
            'phoneNumber' => ['nullable', 'string', 'max:20'],
            'dateOfBirth' => ['nullable', 'date'],
            'arrivalDate' => ['nullable', 'date'],

            // Personal details
            'maritalStatus' => ['nullable', 'string'],
            'numberOfChildren' => ['nullable', 'integer', 'min:0'],
            'seriousIllnesses' => ['nullable', 'string'],

            // Baptism details
            'baptism_type' => ['nullable', 'array'],
            'baptism_nominalMaker' => ['required', 'string'],
            'baptism_hasHolySpirit' => ['nullable', 'boolean'],
            'baptism_ministerialLevel' => ['required', 'string'],
            'baptism_spiritualLevel' => ['nullable', 'integer', 'min:0', 'max:10'],
        ], [
            'baptism_nominalMaker.required' => 'Please specify the baptism official.',
            'baptism_ministerialLevel.required' => 'Please select a ministerial level.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration
     */
    protected function create(array $data)
    {
        // Begin a database transaction to ensure data consistency
        return \DB::transaction(function () use ($data) {
            // Create user
            $user = User::create([
                'name' => $data['name'],
                'surname' => $data['surname'] ?? null,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'gender' => $data['gender'],
                'profession' => $data['profession'],
                'profession_details' => $data['profession_details'] ?? null,
                'residence' => $data['residence'] ?? null,
                'phoneNumber' => $data['phoneNumber'] ?? null,
                'dateOfBirth' => $data['dateOfBirth'] ?? null,
                'arrivalDate' => $data['arrivalDate'] ?? null,
                'maritalStatus' => $data['maritalStatus'] ?? null,
                'numberOfChildren' => $data['numberOfChildren'] ?? 0,
                'seriousIllnesses' => $data['seriousIllnesses'] ?? null,
                'isActive' => true,
                'isDisciplined' => true,
            ]);

            // Create baptism record
            $baptismType = $data['baptism_type']
                ? collect($data['baptism_type'])->join('_')
                : 'none';

            Baptism::create([
                'user_id' => $user->id,
                'type' => $baptismType,
                'nominalMaker' => $data['baptism_nominalMaker'],
                'hasHolySpirit' => $data['baptism_hasHolySpirit'] ?? false,
                'ministerialLevel' => $data['baptism_ministerialLevel'],
                'spiritualLevel' => $data['baptism_spiritualLevel'] ?? 0,
                'date_water' => $data['baptism_date_water'] ?? null,
                'date_holy_spirit' => $data['baptism_date_holy_spirit'] ?? null,
                'date_latest' => $data['baptism_date_latest'] ?? null,
            ]);

            // Assign default end-user role
            $endUserRole = Roles::firstWhere('name', Roles::$END_USER);
            if ($endUserRole) {
                $user->roles()->attach($endUserRole);
            }

            return $user;
        });
    }

    /**
     * Handle a registration request for the application
     */
    public function register(Request $request)
    {
        // Validate the incoming request
        $this->validator($request->all())->validate();

        // Create the user
        $user = $this->create($request->all());

        // Fire the registered event
        event(new Registered($user));

        // Automatically log in the user
        auth()->login($user);

        // Redirect to dashboard or home page
        return redirect(route('dashboard'));
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}
