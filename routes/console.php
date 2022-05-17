<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin',function (){
    User::create([
        'first_name' => 'Batuhan',
        'last_name' => 'OK',
        'password' => bcrypt('123'),
        'tc_verified_at' => now(),
        'tc_kn' => 54637335882,
        'birth_year' => 1996
    ])->syncRoles('super-admin');

    $this->comment('Batuhan OK - 54637335882 - 1996 - Yönetici hesabı oluşturuldu.');
})->purpose('Create admin account');
