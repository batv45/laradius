<?php

namespace App\Http\Requests\Balance;

use Illuminate\Foundation\Http\FormRequest;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardExpirationDate;
use LVR\CreditCard\CardExpirationMonth;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardNumber;

class BalancePaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0|max:500',
        ];
    }

    public function attributes()
    {
        return [
            'amount' => 'Ödeme tutar',
        ];
    }
}
