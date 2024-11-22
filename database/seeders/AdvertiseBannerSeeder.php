<?php

namespace Database\Seeders;

use App\Models\AdvertiseBanner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdvertiseBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();
        $data = [
            [
                'advertiser_name' => 'Advertiser One',
                'ad_name' => 'Summer Sale Banner',
                'target_url' => 'https://example.com/summer-sale',
                'impressions_purchased' => 1000,
                'impressions_served' => 0,
                'impressions_count' => 0,
                'click_count' => 0,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(2)->toDateString(),
                'page_type' => 'home',
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'advertiser_name' => 'Advertiser Two',
                'ad_name' => 'New Product Launch',
                'target_url' => 'https://example.com/new-product',
                'impressions_purchased' => 5000,
                'impressions_served' => 0,
                'impressions_count' => 0,
                'click_count' => 0,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(3)->toDateString(),
                'page_type' => 'add-buyer-details',
                'start_time' => '10:00:00',
                'end_time' => '20:00:00',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        AdvertiseBanner::insert($data);
    }
}

