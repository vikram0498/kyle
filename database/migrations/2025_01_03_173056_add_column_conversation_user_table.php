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
        Schema::table('conversation_user', function (Blueprint $table) {

            $table->boolean('is_block')->default(false)->after('user_id');
            $table->timestamp('blocked_at')->nullable()->after('is_block');
            $table->unsignedBigInteger('blocked_by')->nullable()->after('blocked_at');
            $table->foreign('blocked_by')->references('id')->on('users')->onDelete('set null');
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
        Schema::table('conversation_user', function (Blueprint $table) {
            $table->dropForeign(['blocked_by']); 
            $table->dropColumn(['is_block', 'blocked_at', 'blocked_by','deleted_at']);
        });
    }
};
