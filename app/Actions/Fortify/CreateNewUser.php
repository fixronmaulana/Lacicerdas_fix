<?php

namespace App\Actions\Fortify;

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
        // Custom validation
        Validator::make($input, [
            'name' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z]{1,50}$/'],
            'email' => [
                'required',
                'string',
                'email',
                'max:30',
                function ($attribute, $value, $fail) {
                    // Check uniqueness case-sensitively
                    $exists = User::whereRaw('BINARY email = ?', [$value])->exists();
                    if ($exists) {
                        $fail('The email has already been taken.');
                    }
                },
                'regex:/^[^\d][\w.-]+@[\w.-]+\.[a-zA-Z]{1,30}$/'
            ],
            'password' => $this->passwordRules(),
            'role' => ['required', 'string', 'in:admin,user'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 50 karakter.',
            'name.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
            'email.max' => 'Email tidak boleh lebih dari 30 karakter',
            'password.max' => 'Password tidak boleh lebih dari 50 karakter'
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $input['role'],
        ]);
    }
}
