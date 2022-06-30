<?php

namespace Database\Seeders;

use App\Models\Hotspot;
use App\Models\HotspotAccount;
use App\Models\Router;
use Illuminate\Database\Seeder;

class HotspotSeeder extends Seeder
{
    public function run()
    {
        $router = Router::create([
            'ip_address' => '192.168.1.111',
            'port' => '8728',
            'username' => 'admin',
            'password' => '123'
        ]);

        $hot1 =  Hotspot::create([
            'router_id' => $router->id,
            'name' => 'Hotspot Wifi',
            'description' => 'Bedava wifi açıklamnası',
            'link_login_post' => 'http://10.10.10.1/login'
        ]);
        $hot2 = Hotspot::create([
            'router_id' => $router->id,
            'name' => 'Belediye wifi',
            'description' => 'Soma belediye hotspot wifi',
            'link_login_post' => 'http://10.10.10.1/login'
        ]);


        HotspotAccount::create([
            'hotspot_id' => $hot1->id,
            'first_name' => 'batuhan',
            'last_name' => 'ok',
            'birth_year' => '1996',
            'identity_number' => '123',
            'identity_verified_at' => now()
        ]);
        HotspotAccount::create([
            'hotspot_id' => $hot1->id,
            'first_name' => 'tunahan',
            'last_name' => 'ok',
            'birth_year' => '1998',
            'identity_number' => '222',
            'identity_verified_at' => now()
        ]);
    }
}
