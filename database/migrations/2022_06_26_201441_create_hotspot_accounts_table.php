<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('hotspot_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Hotspot::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('birth_year')->nullable();
            $table->string('phone_number')->nullable();

            $table->dateTime('identity_verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotspot_accounts');
    }
};
