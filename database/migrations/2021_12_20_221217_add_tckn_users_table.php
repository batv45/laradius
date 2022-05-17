<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTcknUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('tc_kn')->unique();
            $table->unsignedInteger('birth_year');
            $table->timestamp('tc_verified_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tc_kn');
            $table->dropColumn('birth_year');
            $table->dropColumn('tc_verified_at');
        });
    }
}
