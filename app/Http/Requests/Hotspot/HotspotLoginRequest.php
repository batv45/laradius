<?php

namespace App\Http\Requests\Hotspot;

use Deligoez\TCKimlikNo\Rules\TCKimlikNoValidate;
use Deligoez\TCKimlikNo\Rules\TCKimlikNoVerify;
use Illuminate\Foundation\Http\FormRequest;

class HotspotLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birth_year' => ['required','numeric','digits:4','min:' . (date('Y')-100),'max:' . date('Y')],
            'identity_number' => ['bail',
                'required_with_all:first_name,last_name,birth_year',
                new TCKimlikNoValidate($this->input('first_name') ?? '',$this->input('last_name') ?? '',$this->input('birth_year') ?? '')

            ],
        ];
    }

    public function attributes()
    {
        return [
          'first_name' => 'Ad',
          'last_name' => 'Soyad' ,
          'birth_year' => 'Doğum Yılı' ,
          'identity_number' => 'TC Kimlik' ,
        ];
    }
}
