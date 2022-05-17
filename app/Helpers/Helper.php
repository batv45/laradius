<?php

if (! function_exists('swal')) {
    /**
     * swal.
     *
     * @param  string|null  $message
     * @param  string|null  $title
     */
    function swal(string $message = null, string $title = null)
    {
        $notify = app('notify');

        if (! is_null($message)) {
            return $notify->success($message, $title);
        }

        return $notify;
    }
}

if (! function_exists('IsNullOrEmptyString')) {
    function IsNullOrEmptyString($str): bool
    {
        return ($str === null || trim($str) === '');
    }
}

if ( !function_exists('expense_amount_split') ){
    function expense_amount_split($amount, $split){
        return ceil($amount / $split);
    }
}


if ( !function_exists('inertia_get_only') ){
    function inertia_get_only(): array|string|null
    {
        return request()->header('X-Inertia-Partial-Data');
    }
}
if ( !function_exists('inertia_has_only') ){
    function inertia_has_only(): bool
    {
        return request()->hasHeader('X-Inertia-Partial-Data');
    }
}
