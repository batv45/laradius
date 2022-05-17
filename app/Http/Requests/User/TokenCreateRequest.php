<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class TokenCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token_name' => 'required|string|max:62'
        ];
    }

    public function attributes()
    {
        return [
            'token_name' => 'Token Adı'
        ];
    }
}
