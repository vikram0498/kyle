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
            $table->json('user_json')->nullable();
            $table->unsignedBigInteger('plan_id'); // If you want to associate transactions with plans
            $table->json('plan_json')->nullable();
            $table->tinyInteger('is_addon')->default(0)->comment('0=>No, 1=Yes');
            $table->string('payment_intent_id')->nullable();
            $table->double('amount', 11, 2)->nullable();
            $table->string('currency')->nullable();
            $table->string('payment_method')->nullable(); // Credit card, bank transfer, etc.
            $table->enum('payment_type',['debit','credit'])->nullable(); 
            $table->json('payment_json')->nullable();
            $table->string('status')->comment('1=>success ,2=>failed'); // 'pending', 'success', 'failure', etc.
            // Add more columns as needed
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
