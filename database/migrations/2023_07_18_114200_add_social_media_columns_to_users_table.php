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
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('register_type')->default(1)->comment('1=> Register page, 2 => Google, 3 => Facebook')->after('phone');
            $table->string('social_id')->nullable()->default(null)->after('register_type');
            $table->json('social_json')->nullable()->default(null)->after('social_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['register_type','social_id','social_json']);
        });
    }
};
