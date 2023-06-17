<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* $settings = [
            [
                'id'     => 1,
                'key'    => 'website_logo',
                'value'  => 'default/logo.png',
                'type'   => 'logo',
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],

            [
                'id'     => 2,
                'key'    => 'facebook',
                'value'  => 'default/logo.png',
                'type'   => 'social media',
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            
        ];

        Setting::insert($settings); */
    }
}
