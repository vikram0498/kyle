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
            $table->string('country_code', 5)->nullable()->after('email');
            $table->tinyInteger('prev_level_type')->default(1)->comment('1=>Level 1, 2=>Level 2, 3=>Level 3')->after('level_type');
            $table->tinyInteger('level_3')->default(0)->comment('1=> active, 0=>deactive')->after('prev_level_type');
            $table->tinyInteger('is_switch_role')->default(null)->after('level_3');
            $table->tinyInteger('is_super_buyer')->default(0)->comment('1=> active, 0=>deactive')->after('is_switch_role');
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
            $table->dropColumn('country_code');
            $table->dropColumn('prev_level_type');
            $table->dropColumn('level_3');
            $table->dropColumn('is_switch_role');
            $table->dropColumn('is_super_buyer');
        });
    }
};
