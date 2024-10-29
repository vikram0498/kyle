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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('campaigns');
            $table->string('page_title');
            $table->enum('target_zone', ['top', 'left', 'bottom', 'right']);
            $table->string('target_url');
            $table->string('target_alt_des')->nullable();
            $table->dateTime('date_to_start');
            $table->dateTime('date_to_end');
            $table->integer('impression_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->enum('status', ['inactive', 'active', 'paused', 'archived'])->default('inactive');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertisements');
    }
};
