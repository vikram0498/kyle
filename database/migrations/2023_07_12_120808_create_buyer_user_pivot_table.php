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
        Schema::create('buyer_user', function (Blueprint $table) {
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('reason')->nullable();
            $table->json('incorrect_info')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 => Not resolved, 1 => Resolved, 2 => Rejcted');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(Null)->nullable();

            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buyer_user');
    }
};
