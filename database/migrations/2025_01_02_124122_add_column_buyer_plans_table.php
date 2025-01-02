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
        Schema::table('buyer_plans', function (Blueprint $table) {
            $table->string('product_stripe_id')->nullable()->after('plan_stripe_id');
            $table->string('price_stripe_id')->nullable()->after('product_stripe_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['product_stripe_id','price_stripe_id']);
        });
    }
};
