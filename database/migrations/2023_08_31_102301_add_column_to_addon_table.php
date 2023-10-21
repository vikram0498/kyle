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
        Schema::table('addons', function (Blueprint $table) {
            $table->string('product_stripe_id')->nullable()->after('id');
            $table->string('price_stripe_id')->nullable()->after('product_stripe_id');

            $table->longtext('product_json')->nullable()->after('credit');
            $table->longtext('price_json')->nullable()->after('product_json');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->dropColumn('product_stripe_id');
            $table->dropColumn('price_stripe_id');
            $table->dropColumn('product_json');
            $table->dropColumn('price_json');
        });

    }
};
