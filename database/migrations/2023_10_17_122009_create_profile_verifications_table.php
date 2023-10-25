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
        Schema::create('profile_verifications', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_id_fk_6053')->references('id')->on('users')->onDelete('cascade');

            $table->string('other_proof_of_fund')->nullable();

            $table->tinyInteger('is_phone_verification')->default(0)->comment('0 -> No, 1 -> Yes');
            $table->tinyInteger('is_driver_license')->default(0)->comment('0 -> No, 1 -> Yes');
            $table->tinyInteger('is_proof_of_funds')->default(0)->comment('0 -> No, 1 -> Yes');
            $table->tinyInteger('is_llc_verification')->default(0)->comment('0 -> No, 1 -> Yes');
            $table->tinyInteger('is_application_process')->default(0)->comment('0 -> No, 1 -> Yes');

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
        Schema::dropIfExists('profile_verifications');
    }
};
