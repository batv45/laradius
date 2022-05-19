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

if ( !function_exists('get_port_ranges') ){
    function get_port_ranges(int $account_limit, int $min_port = 1000, int $max_port = 65535): \Illuminate\Support\Collection
    {
        $ports = collect();
        if($account_limit < 1)
            return $ports;
        $range = ($max_port - ($min_port-1)) / $account_limit;
        $start = $min_port-1;

        for ($i = 1; $i < $account_limit+1; $i++){
            $ran = intval($start+$range);
            $ports->push([
                'port_min'=> $start+1,
                'port_max' => $ran
            ]);
            $start = $ran;
        }
        return $ports;
    }
}
