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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id(); 

            $table->uuid('uuid')->unique();

            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('title')->nullable(); // Title of the conversation (useful for group chats, can be null for 1:1 chats)

            $table->boolean('is_group')->default(false); // Indicates whether it's a group chat (false = 1:1 chat)

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('participants_count')->default(0);

            $table->timestamp('last_message_at')->nullable();

            $table->timestamp('archived_at')->nullable();

            $table->timestamps(); // For created_at and updated_at timestamps

            $table->softDeletes(); // For soft deletion support (deleted_at)

            $table->index(['uuid']); // Index on UUID for better lookup
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};
