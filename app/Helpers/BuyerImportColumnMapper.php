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

        ];

        return $mapping[$columnName] ?? null; 
    }
}
