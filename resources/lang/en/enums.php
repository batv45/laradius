<?php

use App\Enums\HouseType;

return [

    HouseType::class => [
        HouseType::Home => 'Home',
        HouseType::Workplace => 'Workplace',
    ],
    \App\Enums\ExpenseType::class => [
        \App\Enums\ExpenseType::Other => 'Other',
        \App\Enums\ExpenseType::Monthly => 'Monthly',
        \App\Enums\ExpenseType::Service => 'Service'
    ],
    \App\Enums\PaymentType::class => [
        \App\Enums\PaymentType::Cash => 'Cash',
        \App\Enums\PaymentType::Transfer => 'Transfer',
        \App\Enums\PaymentType::CreditCard => 'Credit Card',
        \App\Enums\PaymentType::DebitCard => 'Debit Card'
    ]

];
