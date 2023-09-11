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
        'title'          => 'Sellers',
        'title_singular' => 'Seller',
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
            'buyer_count'              => 'Self Buyers',
            'purchased_buyer'          => 'Purchased Buyers',
            'package'                  => 'Package',
            'level_type'               => 'Level Type',
            'confirm_password'         => 'Password Confirm',
            'role'                     => 'User Level',
            'created_at'               => 'Created at',
            'updated_at'               => 'Updated at',
            'deleted_at'               => 'Deleted at',
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
            'description'   => 'Description',       
            'video'         => 'Video',       
            'status'        => 'Status', 
            'created_at'    => 'Created At',
        ],
    ],

    'buyer' => [
        'title'                 => 'Buyers',
        'sub_menu_list_title'   => 'List of Buyers',
        'sub_menu_flaged_title' => 'Red Flaged Buyers',
        'title_singular'        => 'Buyer',
        'creative_buyer'        => 'Creative Buyer',
        'multi_family_buyer'    => 'Multi Family Buyer',
        'fields' => [
            'user_id'                       => 'Seller',
            'name'                          => 'Name',
            'first_name'                    => 'First Name',
            'last_name'                     => 'Last Name',
            'email'                         => 'Email ID',
            'phone'                         => 'Contact Number',
            'occupation'                    => 'Occupation',
            'replacing_occupation'          => 'Replacing Occupation',
            'company_name'                  => 'Company/LLC',
            'address'                       => 'Address',
            'city'                          => 'City',
            'country'                       => 'Country',
            'state'                         => 'State',
            'zip_code'                      => 'Zip',
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
            'property_flaw'                 => 'Property Flaw',
            'solar'                         => 'Solar ',
            'pool'                          => 'Pool',
            'septic'                        => 'Septic',
            'well'                          => 'Well',
            'age_restriction'               => 'Age restriction',
            'rental_restriction'            => 'Rental Restriction',
            'hoa'                           => 'HOA',
            'tenant'                        => 'Tenant/Occupant',
            'post_possession'               => 'Post Possession',
            'building_required'             => 'Building Required',
            'foundation_issues'             => 'Foundation Issues',
            'mold'                          => 'Mold',
            'fire_damaged'                  => 'Fire Damaged',
            'rebuild'                       => 'Rebuild',
            'squatters'                       => 'Squatters',
            'buyer_type'                    => 'Buyer Type',
            'max_down_payment_percentage'   => 'Maximum Down Payment (%)',
            'max_down_payment_money'        => 'Maximum Down Payment ($)',
            'max_interest_rate'             => 'Maximum Interest Rate (%)',
            'balloon_payment'               => 'Balloon Payment',
            'unit_min'                      => 'Minimum Units',
            'unit_max'                      => 'Maximum Units',
            'building_class'                => 'Building Class',
            'value_add'                     => 'Value Add',
            'purchase_method'               => 'Purchase Method',
            'buyer_csv_template'            => 'Template For CSV Import',
            'buyer_csv_import'              => 'Import Buyers',
            'csv_file'                      => 'CSV File',
            'import_buyers'                 => 'Import Buyer',
            'copy_add_buyer_link'           => 'Copy Buyer Form Link',
            
            'price_min'                     => 'Price (Min)',
            'price_max'                     => 'Price (Max)',
            'stories_min'                   => 'Stories (Min)',
            'stories_max'                   => 'Stories (Max)',
            'zoning'                        => 'Zoning',
            'utilities'                     => 'Utilities',
            'sewer'                         => 'Sewer',
            'market_preferance'             => 'Market Preference',
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
    ],

    'search_log' => [
        'title'                 => 'Seller Search Logs',
        'title_singular'        => 'Seller Search Log',
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
            'tenant'                        => 'Tenant/Occupant',
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
        'total_seller'  => 'Total Sellers',
    ],

    'transaction' => [
        'title'                 => 'Transactions',
        'title_singular'        => 'Transaction',
        'fields' => [
            'user'   => 'User',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'payment_type'   => 'Payment Type',
            'payment_method' => 'Payment Method',
            'status'         => 'Status',
        ],
    ]
];
