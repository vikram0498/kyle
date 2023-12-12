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
            $table->string('reason_type')->nullable()->after('is_application_process');
            $table->text('reason_content')->nullable()->after('reason_type');
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
            $table->dropColumn(['reason_type','reason_content']);
        });
    }
};
