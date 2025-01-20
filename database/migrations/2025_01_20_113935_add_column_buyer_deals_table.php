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
        Schema::table('buyer_deals', function (Blueprint $table) {
            $table->tinyInteger('is_favorite')->default(0)->comment('0=> No, 1=>Yes')->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyer_deals', function (Blueprint $table) {
            $table->dropColumn('is_favorite');
        });
    }
};
