<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutersTable extends Migration
{
    public function up()
    {
        Schema::create('routers', function (Blueprint $table) {
            $table->id();

            $table->ipAddress('ip_address');
            $table->integer('port');
            $table->string('identity')->nullable();
            $table->string('username');
            $table->string('password');
            $table->text('description')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('routers');
    }
}
