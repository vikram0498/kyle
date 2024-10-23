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
        Schema::create('buyer_invitations', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('email')->nullable();
            $table->tinyInteger('reminder_count')->default(0);
            $table->timestamp('last_reminder_sent')->nullable();
            $table->boolean('status')->default(0)->comment('0=> pending, 1=>accepted');
            $table->unsignedBigInteger('created_by')->nullable();
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
        Schema::dropIfExists('buyer_invitations');
    }
};
