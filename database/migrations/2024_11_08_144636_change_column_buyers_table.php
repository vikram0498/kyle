<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE buyers MODIFY purchase_method JSON NULL');
        DB::statement('ALTER TABLE buyers MODIFY property_type JSON NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE buyers MODIFY purchase_method JSON NOT NULL');
        DB::statement('ALTER TABLE buyers MODIFY property_type JSON NOT NULL');
    }
};
