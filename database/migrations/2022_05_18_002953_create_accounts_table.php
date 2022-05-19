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

            $table->foreignIdFor(\App\Models\RouterLanip::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignIdFor(\App\Models\RouterWanip::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('username')->unique();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('address')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
