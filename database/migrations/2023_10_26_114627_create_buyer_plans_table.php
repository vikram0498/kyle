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
        Schema::create('buyer_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_stripe_id')->nullable();
            $table->json('plan_json')->nullable();
            $table->string('title')->nullable();
            $table->enum('type', ['monthly', 'yearly']);
            $table->tinyInteger('position');
            $table->double('amount',15,2)->default(0);
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0=> deactive, 1=> active');
            $table->unsignedBigInteger('user_limit')->default('null')->nullable();
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('buyer_plans');
    }
};
