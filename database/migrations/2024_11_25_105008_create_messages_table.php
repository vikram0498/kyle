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
        
            $table->unsignedBigInteger('conversation_id');
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');

            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        
            $table->longtext('content')->nullable();
            $table->enum('type', ['text', 'image', 'video', 'file'])->default('text');
        
            $table->enum('chat_type', ['direct', 'group'])->default('direct'); 
            //$table->unsignedBigInteger('group_id')->nullable(); // You could add this in the future if you want a direct reference to groups
        
            $table->timestamps();
            $table->softDeletes(); // Good for message deletion management

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
