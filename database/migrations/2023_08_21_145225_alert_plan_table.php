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
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('month_amount');
            $table->dropColumn('year_amount');
            $table->dropColumn('monthly_credit');
            $table->string('plan_token')->nullable()->after('id');
            $table->double('price')->nullable()->after('title');
            $table->enum('type', ['monthly', 'yearly'])->after('price');
            $table->integer('credits')->nullable()->after('type');
            $table->json('plan_json')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('plan_token');
        $table->dropColumn('price');
        $table->dropColumn('type');
        $table->dropColumn('credits');
    }
};
