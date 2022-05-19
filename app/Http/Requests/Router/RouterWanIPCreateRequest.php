<?php

namespace App\Http\Requests\Router;

use Illuminate\Foundation\Http\FormRequest;

class RouterWanIPCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ip_address' => 'required|string',
            'account_limit' => 'required|integer'
        ];
    }

    public function attributes()
    {
        return [
          'ip_address' => 'IP Adres'
        ];
    }
}
