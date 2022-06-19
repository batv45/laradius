<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class AccountUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|string|min:3',
            'password' => 'required|string|min:3',
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:2',
            'email' => 'required|email|max:125|unique:accounts,email,'.$this->route('account'),
            'phone' => 'required|string|max:25',
            'address' => 'nullable|string|max:255',
            'router_id' => 'required|int',
            'router_lanip_id' => 'required|int',
            'router_wanip_id' => 'required|int',
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
