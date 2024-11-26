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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('conversation_id');
        
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->longtext('content')->nullable();
            $table->enum('type',['text','image','video','file'])->default('text');

            $table->enum('chat_type',['direct','group'])->default('direct');
            //$table->unsignedBigInteger('group_id')->nullable(); // For future perspective if chat type is group
            // $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['conversation_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
