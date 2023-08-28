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
            $table->double('price_max')->nullable()->after('price_min');
            $table->tinyInteger('stories_min')->nullable()->after('purchase_method');
            $table->tinyInteger('stories_max')->nullable()->after('stories_min');
            $table->json('zoning')->nullable()->after('of_stories_max');
            $table->tinyInteger('utilities')->nullable()->after('zoning');
            $table->tinyInteger('sewer')->nullable()->after('utilities');
            $table->tinyInteger('market_preferance')->nullable()->after('sewer');
            $table->tinyInteger('contact_preferance')->nullable()->after('market_preferance');
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
            $table->dropColumn('price_max');
            $table->dropColumn('stories_min');
            $table->dropColumn('stories_max');
            $table->dropColumn('zoning');
            $table->dropColumn('utilities');
            $table->dropColumn('sewer');
            $table->dropColumn('market_preferance');
            $table->dropColumn('contact_preferance');
        });
    }
};
