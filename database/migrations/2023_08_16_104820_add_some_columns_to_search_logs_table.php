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
        Schema::table('search_logs', function (Blueprint $table) {
            $table->tinyInteger('stories')->nullable()->after('purchase_method');
            $table->json('zoning')->nullable()->after('of_stories');
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
        Schema::table('search_logs', function (Blueprint $table) {
            $table->dropColumn('stories');
            $table->dropColumn('zoning');
            $table->dropColumn('utilities');
            $table->dropColumn('sewer');
            $table->dropColumn('market_preferance');
            $table->dropColumn('contact_preferance');
        });
    }
};
