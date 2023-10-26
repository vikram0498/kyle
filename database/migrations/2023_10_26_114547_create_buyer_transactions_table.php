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
        Schema::create('buyer_transactions', function (Blueprint $table) {
           
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->json('user_json')->nullable();
            $table->unsignedBigInteger('plan_id');
            $table->json('plan_json')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->decimal('amount', 11, 2);
            $table->string('currency');
            $table->string('payment_method')->nullable(); // Credit card, bank transfer, etc.
            $table->enum('payment_type',['debit','credit'])->nullable(); 
            $table->json('payment_json')->nullable();
            $table->string('status')->comment('1=>success ,2=>failed'); // 'success', 'failure'
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            // Add foreign key constraints and references for plan_id if needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buyer_transactions');
    }
};
