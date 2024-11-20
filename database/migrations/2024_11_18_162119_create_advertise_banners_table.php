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
        Schema::create('advertise_banners', function (Blueprint $table) {
            $table->id();
            $table->string('advertiser_name');
            $table->string('ad_name');
            $table->string('target_url');
            $table->integer('impressions_purchased')->default(0);
            $table->integer('impressions_served')->default(0);
            $table->integer('impressions_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status')->default(0)->comment('0=> inactive, 1=> active,2=> paused, 3=> completed, 4=> archived');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('advertise_banners');
    }
};
