<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Deligoez\TCKimlikNo\Rules\TCKimlikNoValidate;
use Deligoez\TCKimlikNo\Rules\TCKimlikNoVerify;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return User
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255','min:3'],
            'last_name' => ['required', 'string', 'max:255','min:2'],
            'birth_year' => ['required','numeric','digits:4','min:' . (date('Y')-100),'max:' . date('Y')],
            'tc_kn' => ['required','unique:users',new TCKimlikNoVerify()],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->setAttributeNames([
            'first_name' => 'Ad',
            'last_name' => 'Soyad',
            'email' => 'E-posta',
            'password' => 'Şifre',
            'tc_kn' => 'TC Kimlik',
            'birth_year' => 'Doğum Tarihi'
        ])->validate();

        // TC Validation
        Validator::make([
            'tc_kn' => $input['tc_kn']
        ],[
            'tc_kn' => ['required',new TCKimlikNoValidate(
                $input['first_name'],
                $input['last_name'],
                $input['birth_year']
            )]
        ])->setAttributeNames([
            'tc_kn' => 'TC Kimlik',
        ])->validate();

        return User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'tc_kn' => $input['tc_kn'],
            'birth_year' => $input['birth_year'],
            'email' => $input['email'],
            'tc_verified_at' => now(),
            'password' => Hash::make($input['password']),
        ]);
    }
}
