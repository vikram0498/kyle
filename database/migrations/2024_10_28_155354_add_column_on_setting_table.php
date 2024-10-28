<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('settings', function (Blueprint $table) {
            $table->enum('user_type', ['admin', 'seller', 'buyer'])->nullable()->default("admin")->after('group');
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            DB::statement("ALTER TABLE `settings` MODIFY COLUMN `group` ENUM('upload_buyer_video', 'mail', 'links', 'api')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'user_id']);
            DB::statement("ALTER TABLE `settings` MODIFY COLUMN `group` ENUM('upload_buyer_video', 'mail', 'links')");
        });
    }
};
