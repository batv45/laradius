<?php

namespace App\Http\Requests\Router;

use Illuminate\Foundation\Http\FormRequest;

class RouterLanIPCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ip_address' => 'required|string'
        ];
    }

    public function attributes()
    {
        return [
          'ip_address' => 'IP Adres'
        ];
    }
}
