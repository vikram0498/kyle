<?php

namespace App\Helpers;

class BuyerImportColumnMapper
{
    const FIRST_NAME = 0;
    const LAST_NAME = 1;
    const EMAIL = 2;
    const PHONE_NUMBER = 3;
    const CITY  = 4;
    const STATE = 5;
    const COMPANY_NAME = 6;
    const BED_ROOM_MIN = 7;
    const BED_ROOM_MAX = 8;
    const BATH_MIN = 9;
    const BATH_MAX = 10;
    const SIZE_MIN = 11;
    const SIZE_MAX = 12;
    const LOT_SIZE_MIN = 13;
    const LOT_SIZE_MAX = 14;
    const BUILD_YEAR_MIN = 15;
    const BUILD_YEAR_MAX = 16;
    const PRICE_MIN = 44;
    const PRICE_MAX = 45;
    const STORIES_MIN = 46;
    const STORIES_MAX = 47;
    const PROPERTY_TYPE = 18;
    const PARKING = 17;
    const PROPERTY_FLAW = 19;
    const ZONING = 48;
    const UTILITIES = 49;
    const SEWER = 50;
    const MLS_STATUS = 51;
    const CONTACT_PREFERANCE = 52; 
    const ROOMS = 53; 
    const PARK = 54;
    const BUYER_TYPE = 34;
    const PURCHASE_METHODS = 43;
    const SQUATTERS = 55; 
    const PERMANENT_AFFIX = 56;
    const SOLAR = 20; 
    const POOL = 21;
    const SEPTIC = 22;
    const WELL = 23;
    const AGE_RESTRICTION = 24;
    const RENTAL_RESTRICTION = 25;
    const HUA = 26;
    const TENANT = 27;
    const POST_POSSESSION = 28;
    const BUILDING_REQUIRED = 29;
    const FOUNDATION_ISSUES = 30;
    const MOLD = 31;
    const FIRE_DAMAGED = 32;
    const REBUILD = 33;
    const MAX_DOWN_PAYMENT_PERCENTAGE = 35;
    const MAX_DOWN_PAYMENT_MONEY = 36;
    const MAX_INTEREST_RATE = 37;
    const BALLOON_PAYMENT = 38;
    const UNIT_MIN = 39;
    const UNIT_MAX = 40;
    const BUILDING_CLASS = 41;
    const VALUE_ADD = 42;





    public static function getColumnIndex(string $columnName): int
    {
        $mapping = [
            'first_name' => self::FIRST_NAME,
            'last_name'  => self::LAST_NAME,
            'email'      => self::EMAIL,
            'phone_number' => self::PHONE_NUMBER,
            'city'         => self::CITY,
            'state'        => self::STATE,
            'company_name' => self::COMPANY_NAME,
            'bed_room_min' => self::BED_ROOM_MIN,
            'bed_room_max' => self::BED_ROOM_MAX,
            'bath_min' => self::BATH_MIN,
            'bath_max' => self::BATH_MAX,
            'size_min' => self::SIZE_MIN,
            'size_max' => self::SIZE_MAX,
            'lot_size_min' => self::LOT_SIZE_MIN,
            'lot_size_max' => self::LOT_SIZE_MAX,
            'build_year_min' => self::BUILD_YEAR_MIN,
            'build_year_max' => self::BUILD_YEAR_MAX,
            'price_min' => self::PRICE_MIN,
            'price_max' => self::PRICE_MAX,
            'stories_min' => self::STORIES_MIN,
            'stories_max' => self::STORIES_MAX,
            'property_type' => self::PROPERTY_TYPE,
            'parking' => self::PARKING,
            'property_flaw' => self::PROPERTY_FLAW,
            'zoning'        => self::ZONING,
            'utilities'     => self::UTILITIES,
            'sewer'         => self::SEWER,
            'mls_status'    => self::MLS_STATUS,
            'contact_preferance' => self::CONTACT_PREFERANCE,
            'park'          => self::PARK,
            'rooms'         => self::ROOMS,
            'buyer_type'    => self::BUYER_TYPE,
            'purchase_methods' => self::PURCHASE_METHODS,
            'squatters'        => self::SQUATTERS ,
            'permanent_affix'  => self::PERMANENT_AFFIX,
            'solar'            => self::SOLAR,
            'pool'             => self::POOL,
            'septic'           => self::SEPTIC,
            'well'             => self::WELL,
            'age_restriction'  => self::AGE_RESTRICTION,
            'hua'              => self::HUA,
            'tenant'           => self::TENANT,
            'post_possession'  => self::POST_POSSESSION,
            'building_required'  => self::BUILDING_REQUIRED,
            'foundation_issues'  => self::FOUNDATION_ISSUES,
            'mold'              => self::MOLD,
            'fire_damaged'      => self::FIRE_DAMAGED,
            'rebuild'           => self::REBUILD,
            'max_down_payment_percentage' => self::MAX_DOWN_PAYMENT_PERCENTAGE,
            'max_down_payment_money' => self::MAX_DOWN_PAYMENT_MONEY,
            'max_interest_rate' => self::MAX_INTEREST_RATE,
            'balloon_payment'   => self::BALLOON_PAYMENT,
            'unit_min'          => self::UNIT_MIN,
            'unit_max'          => self::UNIT_MAX,
            'building_class'    => self::BUILDING_CLASS,
            'value_add'         => self::VALUE_ADD,
            'rental_restriction' => self::RENTAL_RESTRICTION,

        ];

        return $mapping[$columnName] ?? -1; 
    }
}
