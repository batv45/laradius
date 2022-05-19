<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouterLanipsTable extends Migration
{
    public function up()
    {
        Schema::create('router_lanips', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Router::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->ipAddress('ip_address');

            $table->timestamps();

            $table->unique(['router_id', 'ip_address']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('router_lanips');
    }
}
