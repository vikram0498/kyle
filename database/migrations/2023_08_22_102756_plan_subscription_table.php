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
        Schema::table('plan_subscription', function (Blueprint $table) {
            $table->string('plan_id')->nullable();
            $table->string('subscription_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_subscription', function (Blueprint $table) {
            $table->dropColumn('plan_id');
            $table->dropColumn('subscription_id');

        });
    }
};
