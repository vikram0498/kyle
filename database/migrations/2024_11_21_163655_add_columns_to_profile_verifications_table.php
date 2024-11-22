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
        Schema::table('profile_verifications', function (Blueprint $table) {
            $table->tinyInteger('is_certified_closer')->default(0)->comment('0 -> No, 1 -> Yes')->after('proof_of_funds_status');
            $table->enum('certified_closer_status', ['pending','verified','rejected'])->default('pending')->after('is_certified_closer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_verifications', function (Blueprint $table) {
            $table->dropColumn(['is_certified_closer','certified_closer_status']);
        });
    }
};
