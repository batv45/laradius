<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouterIPsTable extends Migration
{
    public function up()
    {
        Schema::create('router_i_p_s', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Router::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->ipAddress('ip');
            $table->integer('subnet_mask')->default(24);
            $table->string('type');
            $table->integer('account_limit');

            $table->timestamps();

            $table->unique(['router_id','ip']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('router_i_p_s');
    }
}
