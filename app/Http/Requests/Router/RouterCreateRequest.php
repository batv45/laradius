<?php

namespace App\Http\Requests\Router;

use Illuminate\Foundation\Http\FormRequest;

class RouterCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ip_address' => 'required|ipv4|unique:routers',
            'port' => 'required|int',
            'username' => 'required|string',
            'password' => 'required|string',
            'description' => 'nullable|string'
        ];
    }

    public function attributes()
    {
        return [
            'ip_address' => 'IP Adres',
            'port' => 'Port',
            'username' => 'Username',
            'password' => 'Password',
            'description' => 'Not'
        ];
    }
}
