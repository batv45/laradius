<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProfileSettingsUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'notyf_yposition' => 'required|string',
            'notyf_xposition' => 'required|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
