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
            $table->boolean('is_online')->default(1)->comment('1=> Online, 0=>Offline')->after('description');

            $table->unsignedBigInteger('plan_id')->nullable()->after('remember_token');
            $table->foreign('plan_id')->references('id')->on('buyer_plans');

            $table->tinyInteger('is_plan_auto_renew')->default(0)->comment('0=>No, 1=>Yes')->after('plan_id');

            $table->boolean('is_profile_verified')->default(0)->comment('0 => Not Verified, 1 => Verified')->after('is_plan_auto_renew');

            $table->boolean('status')->default(1)->comment('0=> deactive, 1=> active')->after('device_token');
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
            $table->dropColumn(['is_online','plan_id','is_plan_auto_renew','is_profile_verified','status']);
        });
    }
};
