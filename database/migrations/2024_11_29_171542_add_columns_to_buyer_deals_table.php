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
            $table->tinyInteger('is_proof_of_funds')->default(0)->comment('0 -> No, 1 -> Yes')->after('buyer_feedback');
            $table->double('offer_price')->nullable()->after('is_proof_of_funds');
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
            $table->dropColumn(['is_proof_of_funds','offer_price']);
        });
    }
};
