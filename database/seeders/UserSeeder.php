<?php

namespace Database\Seeders;

use App\Models\User;
use Deligoez\TCKimlikNo\Provider\TCKimlikNoFakerProvider;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Factory::create();
        $faker->addProvider(new TCKimlikNoFakerProvider($faker));
        User::create([
            'first_name' => 'Super',
            'last_name' => 'DEDE',
            'email' => 'super@asd.com',
            'password' => bcrypt('123'),
            'tc_verified_at' => now(),
            'tc_kn' => $faker->tckn,
            'birth_year' => 1992
        ])->syncRoles('super-admin');

        User::create([
            'first_name' => 'Admin',
            'last_name' => 'AMCA',
            'email' => 'admin@asd.com',
            'password' => bcrypt('123'),
            'tc_verified_at' => now(),
            'tc_kn' => $faker->tckn,
            'birth_year' => 1992
        ])->syncRoles('admin');
        User::create([
            'first_name' => 'Ali',
            'last_name' => 'BABA',
            'email' => 'ali@asd.com',
            'password' => bcrypt('123'),
            'tc_verified_at' => now(),
            'tc_kn' => $faker->tckn,
            'birth_year' => 1992
        ]);
        User::create([
            'first_name' => 'Veli',
            'last_name' => 'BABA',
            'email' => 'veli@asd.com',
            'password' => bcrypt('123'),
            'tc_verified_at' => now(),
            'tc_kn' => $faker->tckn,
            'birth_year' => 1992
        ]);
    }
}
