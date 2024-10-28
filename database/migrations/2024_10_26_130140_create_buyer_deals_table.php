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
        Schema::create('buyer_deals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid();

            $table->unsignedBigInteger('buyer_user_id');
            $table->unsignedBigInteger('search_log_id');

            $table->text('message')->nullable();
            $table->text('buyer_feedback')->nullable();
            $table->enum('status', ['want_to_buy', 'interested', 'not_interested'])->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('buyer_user_id')->references('id')->on('users');
            $table->foreign('search_log_id')->references('id')->on('search_logs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buyer_deals');
    }
};
