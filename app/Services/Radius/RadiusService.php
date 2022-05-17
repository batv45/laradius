<?php

namespace App\Services\Radius;

use App\Models\Account;
use App\Models\User;

class RadiusService
{
    private string $db_connection;

    public function __construct(string $db_connection)
    {
        $this->db_connection = $db_connection;
    }


    public function syncAccount(Account $account)
    {
        $this->getDB()->table('radcheck')->delete(); // DELETE ALL RADIUS USER ROWS
        $this->getDB()->table('radreply')->delete(); // DELETE ALL RADIUS USER REPLY ROWS

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
                'value' => $account->lan_ip
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

    private function table(string $table)
    {
        return $this->getDB()->table($table);
    }

    private function getDB(): \Illuminate\Database\Connection
    {
        return \DB::connection($this->db_connection);
    }

}
