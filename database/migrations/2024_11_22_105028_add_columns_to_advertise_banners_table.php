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
        Schema::table('advertise_banners', function (Blueprint $table) {
            $table->string('page_type')->nullable()->after('ad_name');
            $table->time('start_time')->nullable()->default(null)->after('end_date');
            $table->time('end_time')->nullable()->default(null)->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertise_banners', function (Blueprint $table) {
            $table->dropColumn(['page_type','start_time','end_time']);
        });
    }
};
