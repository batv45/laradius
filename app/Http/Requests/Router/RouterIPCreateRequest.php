<?php

namespace App\Http\Requests\Router;

use App\Enums\RouterIPType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class RouterIPCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ip' => 'required|ip',
            'subnet_mask' => 'required|integer',
            'type' => ['required',new EnumValue(RouterIPType::class)],
            'account_limit' => 'required|int'
        ];
    }

    public function attributes()
    {
        return [
          'ip' => 'IP',
          'type' => 'IP Tür',
            'account_limit' => 'Abone Sayısı'
        ];
    }
}
