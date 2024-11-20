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
        Schema::create('ad_performance_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advertise_banner_id');
            $table->foreign('advertise_banner_id')->references('id')->on('advertise_banners');
            $table->enum('event_type', ['impression', 'click']);
            $table->ipAddress('user_ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_performance_logs');
    }
};
