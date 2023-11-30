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
            
            $table->enum('driver_license_status', ['pending','verified','rejected'])->default('pending')->after('is_driver_license');
            
            $table->enum('proof_of_funds_status', ['pending','verified','rejected'])->default('pending')->after('is_proof_of_funds');
            
            $table->enum('llc_verification_status', ['pending','verified','rejected'])->default('pending')->after('is_llc_verification');
            
            $table->enum('is_profile_verify', ['pending','verified','rejected'])->default('pending')->after('is_driver_license');
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
            $table->dropColumn(['driver_license_status','proof_of_funds_status','llc_verification_status','is_profile_verify']);
        });
    }
};
