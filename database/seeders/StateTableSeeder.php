<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class StateTableSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeds/sql/states.sql');
        $sql    = file_get_contents($path);
        DB::unprepared($sql);

    }
}
