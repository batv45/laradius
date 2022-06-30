<?php

namespace App\Services\Radius;

use App\Models\Account;
use App\Models\HotspotAccount;
use App\Models\User;

class RadiusService
{
    private string $db_connection;

    public function __construct(string $db_connection)
    {
        $this->db_connection = $db_connection;
    }


    public function syncHotspotAccount(HotspotAccount $account)
    {
        $this->getDB()->table('radcheck')->where('username',$account->username)->delete(); // delete old data
        $this->getDB()->table('radcheck')->insert([
            'username' => $account->username,
            'attribute' => 'SHA2-Password',
            'op' => ':=',
            'value' => hash('sha256','123') // default password
        ]);

    }
    public function writeAccount(Account $account)
    {

        $this->getDB()->table('radcheck')->where('username',$account->getOriginal('username'))->delete(); //
        $this->getDB()->table('radreply')->where('username',$account->getOriginal('username'))->delete(); //

        $this->getDB()->table('radcheck')->insert([
            'username' => $account->username,
            'attribute' => 'SHA2-Password',
            'op' => ':=',
            'value' => hash('sha256',$account->password)
        ]); //
        $this->getDB()->table('radreply')->insert([
            [
                'username' => $account->username,
                'attribute' => 'Framed-Protocol',
                'op' => '=',
                'value' => 'PPP'
            ],
            [
                'username' => $account->username,
                'attribute' => 'Framed-IP-Address',
                'op' => '=',
                'value' => $account->router_lanip->ip_address
            ],
            [
                'username' => $account->username,
                'attribute' => 'Framed-IP-Netmask',
                'op' => '=',
                'value' => '255.255.255.255'
            ],
            [
                'username' => $account->username,
                'attribute' => 'Mikrotik-Rate-Limit',
                'op' => '=',
                'value' => '3000k/6000k'
            ],
        ]); //
    }


    // PRIVATE FUNCS
    private function table(string $table)
    {
        return $this->getDB()->table($table);
    }

    private function getDB(): \Illuminate\Database\Connection
    {
        return \DB::connection($this->db_connection);
    }

}
