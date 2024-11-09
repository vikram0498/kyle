<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Request Type List
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the request type list 
    |
    */ 
    'app_name' => env('APP_NAME'),
    'front_end_url' => env('FRONTEND_URL'),
    'app_mode' => env('APP_MODE','staging'),
    'default' => [
        'logo' => 'images/logo.png',
        'favicon' => 'images/favicon.png',
        'admin_favicon' => 'images/fav-icon.svg',
        'short_logo' => 'images/favicon.png',
        'admin_logo' => 'images/logo.svg',
        'transparent_logo' => 'assets/logo/logo-transparent.png',
        'profile_image' => 'default/default-user-man.png',
        'email_logo' => 'images/email-logo.png',
    ],

    'roles'=>[
        'super_admin'   =>  1,
        'seller'        =>  2,
        'buyer'         =>  3,
    ],

    'profile_image_size' =>'2048', // 1024 = 1 MB

    'owner_name' => env('OWNER_NAME','Administrator'),
    'owner_email' => env('OWNER_MAIL'),
    'twilio_country_code'=>env('TWILIO_COUNTRY_CODE'),
    'buyer_application_free_price_id'=>env('BUYER_APPLICATION_FEE_PRICE_ID'),
    'buyer_profile_update_price_id'=>env('BUYER_PROFILE_UPDATE_PRICE_ID'),

    // 'owner_email' => 'amitpandey.his@gmail.com',
    // 'owner_email' => 'rohithelpfullinsight@gmail.com',
    
    'date_format' => 'd-m-Y',
    'datetime_format' => 'd-m-Y h:i A',
    'search_datetime_format' => '%d-%m-%Y %H:%i',
    'search_date_format' => '%d-%m-%Y',
    'set_timezone' => 'Asia/kolkata', // set timezone
    
    'logo_min_width' => '250', // logo min width
    'logo_min_height' => '150', // logo min height
   
    'img_max_size' => '1024', // In KB
    'video_max_size' => '102400', // 102400 KB => 100MB

    'currency_icon' => 'fa-dollar-sign',

    'copy_right_content'=>'All Rights Reserved.',

    'token_expired_time'=>60,

    'datatable_entries' => [10, 25, 50, 100],

    'number_of_rows' => [
        10 => '10',
        25 => '25',
        50 => '50',
        75 => '75',
        100 => '100',
    ],

    'parking_values' => [
        1 => 'Assigned',
        2 => 'Carport',
        3 => 'Driveway',
        4 => 'Garage - 1 car',
        5 => 'Garage - 2 car',
        6 => 'Garage - 3+',
        7 => 'Off-Street',
        8 => 'On-Street',
        9 => 'Unassigned',
    ],

    'property_types' => [
        // 1 => 'Attached',
        // 2 => 'Apartment Buildings',
        3 => 'Commercial - Retail',
        4 => 'Condo',
        // 5 => 'Detached',
        // 6 => 'Development',
        7 => 'Land',
        8 => 'Manufactured',
        // 9 => 'Mobile Home',
        10 => 'Multi-Family - Commercial',
        11 => 'Multi-Family - Residential',
        12 => 'Single Family',
        13 => 'Townhouse',
        14 => 'Mobile Home Park',
        15 => 'Hotel/Motel',
    ],

    'property_flaws' => [
        1 => 'Railroad',
        2 => 'Major Road',
        3 => 'Boarders Non-residential',
    ],

    'park' => [
        1 => 'Park Owned',
        2 => 'Tenant Owned',        
    ],

    'buyer_types' => [
        // 1 => 'Creative',
        // 2 => 'Single Family Buyer',
        // 3 => 'Multi Family Buyer',
        // 4 => 'Fix & Flip',
        5 => 'Hedgefund',
        // 6 => 'Long Term Rental',
        // 7 => 'Short Term Rental',
        // 8 => 'Wholesaler',
        // 9 => 'Portfolio',
        // 10 => 'Builder',
        11 => 'Investor',
    ],

    'building_class_values' => [
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
    ],
    'purchase_methods' => [
        1 => 'Cash',
        2 => 'Hard Money',
        3 => 'Private Financing',
        4 => 'Conforming Loan',
        5 => 'Creative Finance',
    ],

    'zonings' => [
        1 => 'Agricultural',
        2 => 'Residential',
        3 => 'Multifamily',
        4 => 'Mixed-Use',
        5 => 'Commercial',
        6 => 'Rural',
        7 => 'Industrial',
        8 => 'Recreational',
    ],

    'utilities' => [
        1 => 'Electric On-site',
        2 => 'Electric Available',
        3 => 'Electric Nearby',
        4 => 'No Electric',
    ],

    'sewers' => [
        1 => 'Septic',
        2 => 'Sewer',
        3 => 'None',
    ],

    'market_preferances' => [
        1 => 'On-Market',
        2 => 'Off-Market',
        3 => 'No Preference',
    ],

    'contact_preferances' => [
        1 => 'Email',
        2 => 'Text',
        3 => 'Call',
        4 => 'No Preference',
    ],

    


    'radio_buttons_fields' => [
        'permanent_affix' => [
            [ 'id' => 'permanent_affix_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'permanent_affix_no', 'value' => '0', 'label' => 'no' ],
        ],
        'solar' => [
            [ 'id' => 'solar_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'solar_no', 'value' => '0', 'label' => 'no' ],
        ],
        'pool' => [
            [ 'id' => 'pool_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'pool_no', 'value' => '0', 'label' => 'no' ],
        ],
        'septic' => [
            [ 'id' => 'septic_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'septic_no', 'value' => '0', 'label' => 'no' ],
        ],
        'well' => [
            [ 'id' => 'well_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'well_no', 'value' => '0', 'label' => 'no' ],
        ],
        'hoa' => [            
            [ 'id' => 'hoa_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'hoa_no', 'value' => '0', 'label' => 'no' ],
        ],
        'age_restriction' => [
            [ 'id' => 'age_restriction_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'age_restriction_no', 'value' => '0', 'label' => 'no' ],
        ],
        'rental_restriction' => [            
            [ 'id' => 'rental_restriction_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'rental_restriction_no', 'value' => '0', 'label' => 'no' ],
        ],
        'post_possession' => [            
            [ 'id' => 'post_possession_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'post_possession_no', 'value' => '0', 'label' => 'no' ],
        ],
        'tenant' => [
            [ 'id' => 'tenant_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'tenant_no', 'value' => '0', 'label' => 'no' ],
        ],

        'squatters' =>[
            [ 'id' => 'squatters_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'squatters_no', 'value' => '0', 'label' => 'no' ],
        ],
       
        'building_required' => [            
            [ 'id' => 'building_required_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'building_required_no', 'value' => '0', 'label' => 'no' ],
        ],
        'rebuild' => [            
            [ 'id' => 'rebuild_issues_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'rebuild_issues_no', 'value' => '0', 'label' => 'no' ],
        ],
        'foundation_issues' => [            
            [ 'id' => 'foundation_issues_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'foundation_issues_no', 'value' => '0', 'label' => 'no' ],
        ],
        'mold' => [            
            [ 'id' => 'mold_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'mold_no', 'value' => '0', 'label' => 'no' ],
        ],
        'fire_damaged' => [            
            [ 'id' => 'fire_damaged_issues_yes', 'value' => '1', 'label' => 'yes' ],
            [ 'id' => 'fire_damaged_issues_no', 'value' => '0', 'label' => 'no' ],
        ],
        
    ],

    'default_currency' => env('DEFAULT_CURRENCY','usd'),
    'default_country' => 233,
    'video_title_limit' => 50,

    'buyer_profile_verification_status' => [
        'pending'     => "Pending",
        'verified'    => "Verified",
        'rejected'    => "Rejected"
    ],

    'ids_reason_type'=>[
        'invalid id'      => "Invalid ID",
        'blurry image'    => "Blurry Image",
        'expired id'      => "Expired ID",
        'other'           => "Other",
    ],

    'pof_reason_type'=>[
        'amount required'           => "Amount/Balance Required",
        'failed screening'          => "Failed Screening",
        'name does not match'       => "Name Doesn't Match",
        'insufficient information'  => "Insufficient Information",
        'other'                     => "Other",
    ],

    'buyer_csv_file_row_limit' => 10000,

    'invitation_status' => [
        0 => 'pending',
        1 => 'accepted',
    ],

    'reminders' => [
        1 => 'reminder 1',
        2 => 'reminder 2',
        3 => 'reminder 3',
    ],

    'reminder_mail_subject'=>'Pre-Launch Invite | The Future of Real Estate Investing',

    "buyer_deal_status" => [
        'want_to_buy'      => "Want to Buy",
        "interested"        => "Interested",
        "not_interested"    => "Not Interested",
    ],

    "user_settings" => [
        "deal_custom_message" => [
            'value' => "",
            'type'  => "textarea",
            'display_name' => "Deal Custom Message",
            'setting_for'  => ['seller']
        ]
    ],

    "firebase_json_file" => storage_path('app/firebase-auth.json'),

    "user_notification_settings" => [
        "deal_notification" => [
            'value' => "enable",
            'display_name' => "Deal Notifications",
            'setting_for'  => ['buyer'],
            'push' => [
                'enabled' => false,
            ],
            'email' => [
                'enabled' => false,
            ],
        ],
        "interest_notification" => [
            'value' => "enable",
            'display_name' => "Interest Notifications",
            'setting_for'  => ['seller'],
            'push' => [
                'enabled' => false,
            ],
            'email' => [
                'enabled' => false,
            ],
        ],
        "new_buyer_notification" => [
            'value' => "enable",
            'display_name' => "New Buyers Notification",
            'setting_for'  => ['seller'],
            'push' => [
                'enabled' => false,
            ],
            'email' => [
                'enabled' => false,
            ],
        ],
        "dm_notification" => [
            'value' => "enable",
            'display_name' => "DM Notifications",
            'setting_for'  => ['seller', 'buyer'],
            'push' => [
                'enabled' => false,
            ],
            'email' => [
                'enabled' => false,
            ],
        ],
    ]
];
