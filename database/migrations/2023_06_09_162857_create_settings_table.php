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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191)->default(null)->nullable();
            $table->text('value')->default(null)->nullable();
            $table->string('type',191)->default(null)->nullable();
            $table->string('display_name',191)->default(null)->nullable();
            $table->text('details')->default(null)->nullable();
            $table->enum('group', ['upload_buyer_video','mail'])->nullable();
            $table->tinyInteger('status')->default(1)->comment('0=> inactive, 1=> active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(Null)->nullable();
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
        Schema::dropIfExists('settings');
    }
};
