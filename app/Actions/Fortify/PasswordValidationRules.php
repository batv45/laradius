<?php

namespace App\Actions\Fortify;


use App\Actions\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', new Password, 'confirmed'];
    }
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function nullablePasswordRules(): array
    {
        return ['nullable', 'string', new Password];
    }
}
