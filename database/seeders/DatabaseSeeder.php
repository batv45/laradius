<?php

namespace Database\Seeders;

use App\Models\HotspotAccount;
use Epigra\TrGeoZones\Database\Seeders\TrGeoZonesDatabaseSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //region Required Seeders
        $this->call(TrGeoZonesDatabaseSeeder::class);
        $this->call(PermissionSeeder::class);
        //endregion

        $this->call(UserSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(HotspotSeeder::class);
//        \App\Models\User::factory(22)->create();
    }
}
