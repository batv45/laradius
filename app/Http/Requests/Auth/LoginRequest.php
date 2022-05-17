<?php

namespace App\Http\Requests\Auth;

use Deligoez\TCKimlikNo\Rules\TCKimlikNoVerify;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Fortify;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tc_kn' => ['required',new TCKimlikNoVerify()],
            'password' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes()
    {
        return [
            'tc_kn' => 'T.C. Kimlik'
        ];
    }
}
