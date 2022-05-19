<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouterWanipsTable extends Migration
{
    public function up()
    {
        Schema::create('router_wanips', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Router::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->ipAddress('ip_address');
            $table->integer('port_min');
            $table->integer('port_max');

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('router_wanips');
    }
}
