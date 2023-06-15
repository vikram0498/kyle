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
    'app_name' => 'Kyle',
    'app_mode' => env('APP_MODE','staging'),
    'default' => [
        'logo' => 'images/logo.png',
        'favicon' => 'images/favicon.png',
        'admin_favicon' => 'images/favicon.png',
        'short_logo' => 'images/favicon.png',
        'admin_logo' => 'images/logo.png',
        'transparent_logo' => 'assets/logo/logo-transparent.png',
        'profile_image' => 'default/default-user-man.png',
    ],
    
    'date_format' => 'd-m-Y',
    'datetime_format' => 'd-m-Y h:i:s A',
    'set_timezone' => 'Asia/kolkata', // set timezone
    
    'logo_min_width' => '250', // logo min width
    'logo_min_height' => '150', // logo min height
   
    'img_max_size' => '1024', // In KB
    'video_max_size' => '102400', // 102400 KB => 100MB

    'currency_icon' => 'fa-dollar-sign',

    'copy_right_content'=>'All Rights Reserved.',

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
        1 => 'Attached',
        2 => 'Apartment Buildings',
        3 => 'Commercial - Retail',
        4 => 'Condo',
        5 => 'Detached',
        6 => 'Development',
        7 => 'Land',
        8 => 'Manufactured',
        9 => 'Mobile Home',
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
        3 => 'Boarders non-residential',
    ],

    'buyer_types' => [
        1 => 'Creative',
        2 => 'Single Family Buyer',
        3 => 'Multi Family Buyer',
        4 => 'Fix & Flip',
        5 => 'Hedgefund',
        6 => 'Long Term Rental',
        7 => 'Short Term Rental',
        8 => 'Wholesaler',
        9 => 'Portfolio',
        10 => 'Builder',
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
];