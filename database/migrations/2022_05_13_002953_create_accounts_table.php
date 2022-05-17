<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Router::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('username');
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('address')->nullable();

            $table->ipAddress('lan_ip');
            $table->ipAddress('wan_ip');
            $table->integer('wan_port_min');
            $table->integer('wan_port_max');

            $table->timestamps();

            $table->unique(['router_id','username']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
