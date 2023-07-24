<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CityTableSeeder extends Seeder
{
    public function run()
    {
        $path 	= database_path('seeds/sql/cities.sql');
        $sql 	= file_get_contents($path);
        $statements = array_filter(array_map('trim', explode(';', $sql)));

        foreach ($statements as $stmt) {
            DB::statement($stmt);
        }
    }
}
