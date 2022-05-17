<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Permission::create(['name' => 'user.show','description'=>'Kullanıcı Görüntüleme']);
        Permission::create(['name' => 'user.create','description'=>'Kullanıcı Oluşturma']);
        Permission::create(['name' => 'user.edit','description'=>'Kullanıcı Güncelleme']);
        Permission::create(['name' => 'user.delete','description'=>'Kullanıcı Silme']);

        Permission::create(['name' => 'account.show','description'=>'Abone Görüntüleme']);
        Permission::create(['name' => 'account.create','description'=>'Abone Oluşturma']);
        Permission::create(['name' => 'account.edit','description'=>'Abone Güncelleme']);
        Permission::create(['name' => 'account.delete','description'=>'Abone Silme']);

        Permission::create(['name' => 'test.show','description'=>'Örnek Yetki']);

        Role::create([
            'name' => 'super-admin',
            'description' => 'Sistem yöneticisi'
        ]);

        Role::create([
            'name' => 'admin',
            'description' => 'Yetkili'
        ])->givePermissionTo(['user.show','user.edit','user.create','account.show']);

        Role::create([
            'name' => 'user',
            'description' => 'Kullanıcı'
        ]);

    }
}
