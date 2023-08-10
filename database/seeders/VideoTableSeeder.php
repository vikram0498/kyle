<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoTableSeeder extends Seeder
{
    public function run()
    {
        $videoRecord = [
            [
                'video_key'      => 'upload_buyer_video',
                'title'          => 'Don’t Know How to Upload',
                'description'    => 'Upload multiple buyer or single buyer',
                'status'         => 1,
                'created_by'     => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ], 
        ];

        Video::insert($videoRecord);

    }
}
