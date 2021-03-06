<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('hotspots', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Router::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('link_login_post');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotspots');
    }
};
