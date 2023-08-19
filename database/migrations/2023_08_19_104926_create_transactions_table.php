<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // If you want to associate transactions with users
            $table->double('amount', 11, 2);
            $table->string('currency');
            $table->string('status'); // 'pending', 'success', 'failure', etc.
            $table->string('payment_method'); // Credit card, bank transfer, etc.
            // Add more columns as needed
            $table->json('payment_json')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
