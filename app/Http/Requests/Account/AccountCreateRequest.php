<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class AccountCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|string|min:3',
            'password' => 'required|string|min:3',
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:2',
            'email' => 'required|email|max:125',
            'phone' => 'required|string|max:25',
            'address' => 'nullable|string|max:255',
            'lan_ip' => 'required|ipv4',
            'wan_ip' => 'required|ipv4',
            'lan_subnet' => 'required|integer',
            'wan_subnet' => 'required|integer',
            'wan_port' => 'required|array',
            'router_id' => 'required|integer|exists:routers,id'
        ];
    }

    public function attributes()
    {
        return [
            'username' => 'PPPoE Kullanıcı Ad',
            'password' => 'PPPoE Şifre',
            'first_name' => 'Ad',
            'last_name' => 'Soyad',
            'email' => 'E-Posta',
            'phone' => 'Telefon',
            'address' => 'Adres',
            'lan_ip' => 'LAN IP',
            'wan_ip' => 'WAN IP'
        ];
    }

}
