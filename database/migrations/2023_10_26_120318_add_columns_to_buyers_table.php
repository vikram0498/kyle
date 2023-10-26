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
        Schema::table('buyers', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable()->after('buyer_user_id');
            $table->foreign('plan_id')->references('id')->on('buyer_plans');

            $table->tinyInteger('is_plan_auto_renew')->default(0)->comment('0=>No, 1=>Yes')->after('plan_id');
            $table->tinyInteger('is_profile_payment')->default(0)->comment('0=>No, 1=>Yes')->after('is_plan_auto_renew');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->dropColumn(['plan_id','is_plan_auto_renew','is_profile_payment']);
        });
    }
};
