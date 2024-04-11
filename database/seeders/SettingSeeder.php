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
        $settings = [
            [

                'key'    => 'buyer_video_title',
                'value'  => 'Main Title...',
                'type'   => 'text',
                'display_name'  => 'Title',
                'group'  => 'upload_buyer_video',
                'details' => null,
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
              
            ],
            [

                'key'    => 'buyer_video_sub_title',
                'value'  => 'uploaded video sub title.........',
                'type'   => 'text',
                'display_name'  => 'Sub Title',
                'group'  => 'upload_buyer_video',
                'details' => null,
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
              
            ],
            [

                'key'    => 'buyer_video_description',
                'value'  => null,
                'type'   => 'text_area',
                'display_name'  => 'Description',
                'group'  => 'upload_buyer_video',
                'details' => null,
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
           
            ],
            // [

            //     'key'    => 'buyer_video_image',
            //     'value'  => null,
            //     'type'   => 'image',
            //     'display_name'  => 'Image',
            //     'group'  => 'upload_buyer_video',
            //     'details' => null,
            //     'status' => 1,
            //     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
               
            // ],
            [

                'key'    => 'buyer_video',
                'value'  => null,
                'type'   => 'video',
                'display_name'  => 'Video',
                'group'  => 'upload_buyer_video',
                'details' => null,
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                
            ],

            [

                'key'    => 'reminder_one_mail_content',
                'value'  =>  null,
                'type'   => 'text_area',
                'display_name'  => 'Reminder Mail 1 Content',
                'group'  => 'mail',
                'details' => '[INVITATION_LINK], [APP_NAME]',
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],

            [

                'key'    => 'reminder_two_mail_content',
                'value'  =>  null,
                'type'   => 'text_area',
                'display_name'  => 'Reminder Mail 2 Content',
                'group'  => 'mail',
                'details' => '[INVITATION_LINK], [APP_NAME]',
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],

            [

                'key'    => 'reminder_three_mail_content',
                'value'  =>  null,
                'type'   => 'text_area',
                'display_name'  => 'Reminder Mail 3 Content',
                'group'  => 'mail',
                'details' => '[INVITATION_LINK], [APP_NAME]',
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            
        ];

        Setting::insert($settings); 
    }
}
