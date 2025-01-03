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

            $table->json('product_json')->nullable()->after('plan_json');
            $table->json('price_json')->nullable()->after('product_json');
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
            $table->dropColumn(['product_stripe_id','price_stripe_id','product_json','price_json']);
        });
    }
};
