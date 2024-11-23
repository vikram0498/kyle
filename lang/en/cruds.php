<?php

return [
    'userManagement' => [
        'title'          => 'User Management',
        'title_singular' => 'User Management',
    ],
    'setting' => [
        'title'          => 'Settings',
        'title_singular' => 'Setting',
    ],
    'user'           => [
        'title'          => 'Users',
        'title_singular' => 'User',
        'fields'         => [
            'id'                       => 'ID',
            'first_name'               => 'First Name',
            'last_name'                => 'Last Name',
            'name'                     => 'Name',
            'full_name'                => 'Full name',
            'email'                    => 'Email',
            'phone'                    => 'Phone Number',
            'profile_image'            => 'Profile Image',
            'status'                   => 'Status',
            'block_status'             => 'Block Status',
            'password'                 => 'Password',
            'buyer_count'              => 'Buyers Uploaded',
            'purchased_buyer'          => 'Purchased Buyers',
            'package'                  => 'Package',
            'level_type'               => 'Level Type',
            'level_3'                  => 'Level 3',
            'company_name'             => 'Company Name',
            'confirm_password'         => 'Password Confirm',
            'role'                     => 'User Level',
            'created_at'               => 'Created',
            'updated_at'               => 'Updated',
            'deleted_at'               => 'Deleted',
        ],
    ],
    'permission'     => [
        'title'          => 'Permissions',
        'title_singular' => 'Permission',
        'fields'         => [
            'id'                => 'ID',
            'title'             => 'Title',
            'created_at'        => 'Created at',
            'updated_at'        => 'Updated at',
            'deleted_at'        => 'Deleted at',
        ],
    ],
   
    'plan' => [
        'title' => 'Plans',
        'title_singular' => 'Plan',
        'fields' => [
            'title' => 'Plan Name',
            'price' => 'Price',
            'type'  => 'Type',
            'credits'  => 'Credits',
            // 'month_amount'      => 'Plan month price',
            // 'year_amount'       => 'Plan year price',
            // 'monthly_credit'   => 'Credits per month',
            'description'       => 'Description',
            'image'             => 'Image',          
            'status'            => 'Status', 
            'created_at'        => 'Created At',

        ],
    ],

    'addon' => [
        'title' => 'Additional Plans',
        'title_singular' => 'Additional Plan',
        'fields' => [
            'title'         => 'Name',
            'price'         => 'Price',       
            'credit'        => 'Credit',       
            'status'        => 'Status', 
            'created_at'    => 'Created At',

        ],
    ],
    'video' => [
        'title'             => 'Videos',
        'title_singular'    => 'Video',
        'fields' => [
            'title'         => 'Title',
            'sub_title'     => 'Sub Title',
            'description'   => 'Description',       
            'video'         => 'Video',       
            'status'        => 'Status', 
            'created_at'    => 'Created At',
        ],
    ],

    'multi_select' => '(Multi-Select)',
    'buyer' => [
        'title'                 => 'Buyers',
        'sub_menu_list_title'   => 'List',
        'sub_menu_flaged_title' => 'Red Flaged Buyers',
        'title_singular'        => 'Buyer',
        'creative_buyer'        => 'Creative Buyer',
        'multi_family_buyer'    => 'Multi Family Buyer',
        'fields' => [
            'user_id'                       => 'User',
            'name'                          => 'Name',
            'first_name'                    => 'First Name',
            'last_name'                     => 'Last Name',
            'email'                         => 'Email Address',
            'phone'                         => 'Phone Number',
            'phone_placeholder'             => 'Eg.5055325532',
            'occupation'                    => 'Occupation',
            'replacing_occupation'          => 'Replacing Occupation',
            'company_name'                  => 'Company/LLC',
            'address'                       => 'Address',
            'city'                          => 'Buy Box Criteria City',
            'country'                       => 'Country',
            'state'                         => 'Buy Box Criteria State',
            'zip_code'                      => 'Buy Box Criteria Pin Code',
            'bedroom_min'                   => 'Bedroom (min)',
            'bedroom_max'                   => 'Bedroom (max)',
            'bath_min'                      => 'Bath (min)',
            'bath_max'                      => 'Bath (max)',
            'size_min'                      => 'Sq Ft Min',
            'size_max'                      => 'Sq Ft Max',
            'lot_size_min'                  => 'Lot Size Sq Ft (min)',
            'lot_size_max'                  => 'Lot Size Sq Ft (max)',
            'build_year_min'                => 'Year Built (min)',
            'build_year_max'                => 'Year Built (max)',
            'arv_min'                       => 'ARV (min)',
            'arv_max'                       => 'ARV (max)',
            'parking'                       => 'Parking',
            'property_type'                 => 'Property Type',
            'property_flaw'                 => 'Location Flaws',
            'solar'                         => 'Solar ',
            'pool'                          => 'Pool',
            'septic'                        => 'Septic',
            'well'                          => 'Well',
            'age_restriction'               => 'Age restriction',
            'rental_restriction'            => 'Rental Restriction',
            'hoa'                           => 'HOA',
            'tenant'                        => 'Tenant Conveys',
            'post_possession'               => 'Post Possession',
            'building_required'             => 'Building Required',
            'foundation_issues'             => 'Foundation Issues',
            'mold'                          => 'Mold',
            'fire_damaged'                  => 'Fire Damaged',
            'rebuild'                       => 'Rebuild',
            'squatters'                       => 'Squatters',
            'buyer_type'                    => 'Buyer Type',
            'max_down_payment_percentage'   => 'Down Payment (%)',
            'max_down_payment_money'        => 'Down Payment ($)',
            'max_interest_rate'             => 'Interest Rate (%)',
            'balloon_payment'               => 'Balloon Payment',
            'unit_min'                      => 'Minimum Units',
            'unit_max'                      => 'Maximum Units',
            'building_class'                => 'Building Class',
            'value_add'                     => 'Value Add',
            'purchase_method'               => 'Purchase Method',
            'buyer_csv_template'            => 'Download CSV Template',
            'buyer_csv_import'              => 'Upload CSV File',
            'csv_file'                      => 'CSV File',
            'import_buyers'                 => 'Import Buyer',
            'copy_add_buyer_link'           => 'Copy Buyer Form Link',
            
            'price_min'                     => 'Price (Min)',
            'price_max'                     => 'Price (Max)',
            'stories_min'                   => 'Stories (Min)',
            'stories_max'                   => 'Stories (Max)',
            'zoning'                        => 'Zoning',
            'utilities'                     => 'Utilities',
            'sewer'                         => 'Sewage',
            'market_preferance'             => 'MLS Status',
            'park'                          => 'Park Owned/Tenant Owned',
            'rooms'                         => 'Rooms',
            'permanent_affix'               => 'Permanently Affixed',
            'contact_preferance'            => 'Contact Preference',

            'like'                          => 'Like',
            'dislike'                       => 'Dislike',
            'flag_mark'                     => 'Flag Mark',
            'is_ban'                        => 'Ban Status',
            'status'                        => 'Status', 
            'created_at'                    => 'Created At',
        ],
        'red_flag_view' => [
            'heading'   => 'Red Flag',
            'resolve_all_btn'   => 'Resolve All Flag',
            'resolve_flag_btn'   => 'Resolve Flag',
            'reject_flag_btn'   => 'Reject Flag',
            'buyer_info_heading'   => 'Buyer Information',
            'heading'   => 'Buyer Red Flag',
            'heading'   => 'Buyer Red Flag',
            'heading'   => 'Buyer Red Flag',
        ],
        'profile_verification' => [
            'title' => 'Profile Verification',
            'phone_verification'    => 'Phone Verification',
            'driver_license'        => 'Driver’s License',
            'proof_of_funds'        => 'Proof of Funds',
            'certified_closer'      => 'Certified Closer',
            'llc_verification'      => 'LLC Verification',
            'application_process'   => 'Application Process',
            'phone_verification'    => 'Phone Verification',
            'front_id_photo'        => 'Front ID Photo',
            'back_id_photo'         => 'Back ID Photo',
            'bank_statement'        => 'Bank Statement',
            'settlement_statement'  => 'Settlement Statement',
            'other_proof_fund'      => 'Other Proof Of Fund'
        ]
    ],

    'search_log' => [
        'title'                 => 'User Search Logs',
        'title_singular'        => 'User Search Log',
        'fields' => [
            'user_id'                       => 'User Name',
            'address'                       => 'Address',
            'city'                          => 'City',
            'state'                         => 'State',
            'zip_code'                      => 'Zip',
            'bedroom'                       => 'Bedroom',
            'bath'                          => 'Bath',
            'size'                          => 'Size',
            'lot_size'                      => 'Lot Size Sq Ft',
            'build_year'                    => 'Year Built',
            'arv'                           => 'ARV',
            'parking'                       => 'Parking',
            'property_type'                 => 'Property Type',
            'property_flaw'                 => 'Property Flaw',
            'solar'                         => 'Solar ',
            'pool'                          => 'Pool',
            'septic'                        => 'Septic',
            'well'                          => 'Well',
            'age_restriction'               => 'Age restriction',
            'rental_restriction'            => 'Rental Restriction',
            'hoa'                           => 'HOA',
            'squatters'                     => 'Squatters',
            'tenant'                        => 'Tenant Conveys',
            'post_possession'               => 'Post Possession',
            'building_required'             => 'Building Required',
            'foundation_issues'             => 'Foundation Issues',
            'mold'                          => 'Mold',
            'fire_damaged'                  => 'Fire Damaged',
            'rebuild'                       => 'Rebuild',
            'purchase_method'               => 'Purchase Method',
            'status'                        => 'Status', 
            'created_at'                    => 'Created At',
        ],
    ],

    'dashboard'  => [
        'total_buyer'  => 'Total Buyers',
        'total_seller'  => 'Total Users',
    ],

    'transaction' => [
        'title'                 => 'Transactions',
        'title_singular'        => 'Transaction',
        'fields' => [
            'user'   => 'User',
            'plan'   => 'Plan',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'payment_type'   => 'Payment Type',
            'payment_method' => 'Payment Method',
            'status'         => 'Status',
        ],
    ],

    'support'=>[
        'title'=>'Supports',
        'title_singular'=>'Support',
        'fields'=>[
            'name'  => 'Name',
            'email' => 'Email',
            'phone_number' => 'Phone Number',
            'contact_preferance' => 'Contact Preference',
            'message' => 'Message',
            'created_at' => 'Created At',
        ]

        ],


    'buyer_plan' => [
        'title' => 'Profile Tags',
        'title_singular' => 'Profile Tag',
        'fields' => [
            'title' => 'Tag Name',
            'amount' => 'Amount',
            'type'  => 'Type',
            'position'  => 'Rank',
            'description'       => 'Description',
            'image'             => 'Image',          
            'status'            => 'Status', 
            'user_limit'        => 'User Limit', 
            'color'             => 'Color', 
            'created_at'        => 'Created At',
        ],
    ],

    'buyer_transaction' => [
        'title'                 => 'Transactions',
        'title_singular'        => 'Transaction',
        'fields' => [
            'user'   => 'User',
            'plan'   => 'Plan',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'description' => 'Description',
            'payment_type'   => 'Payment Type',
            'payment_method' => 'Payment Method',
            'status'         => 'Status',
        ],
    ],

    'buyer_verification'=>[
        'title'           => 'Buyer Verification',

    ],

    'buyer_invitation'=>[
        'title'        => 'Invited List',
        'remider_btn'  => 'Reminder',
        'fields' => [
            'email'              => 'Email',
            'seller_name'        => 'Seller/Agent Name',
            'reminder'           => 'Reminder',
            'last_reminder_sent' => 'Last Reminder Sent',
            'status'             => 'Status',
        ]

    ],

    'adBanner' => [
        'title' => 'Ad Banners',
        'title_singular' => 'Ad Banner',
        'fields' => [
            'advertiser_name'       => 'Advertiser Name',
            'ad_name'               => 'Ad Name',
            'target_url'            => 'Target Url',
            'impressions_purchased' => 'Impressions Purchased',
            'impressions_served'    => 'Impressions Served',
            'impressions_count'     => 'Impressions Count',
            'click_count'           => 'Click Count',
            'start_date'            => 'Start Date',
            'end_date'              => 'End Date',
            'image'                 => 'Image',          
            'status'                => 'Status', 
            'created_at'            => 'Created At',
            'event_type'            => 'Event Type',
            'user_ip'               => 'User IP',
            'date'                  => 'Date',
            'page_type'             => 'Page Type',
            'start_time'            => 'Start Time',
            'end_time'              => 'End Time',
        ],
        'view_ad_performace_log' => 'View Ad Performance Log',        
    ],
];
