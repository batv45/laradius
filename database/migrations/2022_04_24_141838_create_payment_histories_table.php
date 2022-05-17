<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();

            $table->text('description')->nullable();

            $table->decimal('price', 9, 2);
            $table->string('result_payment_id')->nullable();
            $table->json('raw_result')->nullable();

            $table->timestamp('paymented_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_histories');
    }
}
