<?php

namespace App\Http\Requests\User;

use App\Actions\Fortify\PasswordValidationRules;
use Deligoez\TCKimlikNo\Rules\TCKimlikNoVerify;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    use PasswordValidationRules;
    public function rules()
    {
        $usid = $this->route()->parameter('user');
        return [
            'first_name' => ['required', 'string', 'max:255','min:3'],
            'last_name' => ['required', 'string', 'max:255','min:2'],
            'email' => ['required', 'email'],
            'birth_year' => ['required','numeric','digits:4','min:' . (date('Y')-100),'max:' . date('Y')],
            'tc_kn' => ['required', Rule::unique('users')->ignore($usid),new TCKimlikNoVerify()],
            'new_password' => $this->nullablePasswordRules()
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'first_name' => 'Ad',
            'last_name' => 'Soyad',
            'email' => 'E-Posta',
            'new_password' => 'Yeni Şifre',
            'tc_kn' => 'TC Kimlik',
            'birth_year' => 'Doğum Tarihi'
        ];

    }
}
