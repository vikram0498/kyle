<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromArray;

class BuyersExport implements FromArray, WithHeadings, ShouldAutoSize
{
    
    public function array(): array
    {
        return [
            [
            'Sample', 'Sample','Sample@sample', '1234567890', 'Sample', 'Sample', 'Sample', '123456', 'Sample', 'sample', 'Sample/Blank',

            '11', '22', '11', '22', '11', '22', '11', '22', '2012', '2023', '11/Blank', '22/Blank',

            'sample,sample1', 'sample,sample1/Blank', 'sample,sample1/Blank',

            'yes/no/blank', 'yes/no/blank', 'yes/no/blank', 'yes/no/blank', 'yes/no/blank', 'yes/no/blank', 'yes/no/blank', 'yes/no/blank', 'yes/no/blank', 'yes/no/blank', 'yes/no/blank', 'yes/no/blank', 'yes/no/blank', 'yes/no/blank',

            'sample,sample1', 
            
            '(if Buyer Type= Creative => 1234)/Blank', '(if Buyer Type= Creative => 1234/Blank)/Blank', '(if Buyer Type= Creative => 1234)/Blank', '(if Buyer Type= Creative => yes/no)/Blank',

            '(if Buyer Type= Multi Family Buyer => 11)/Blank', '(if Buyer Type= Multi Family Buyer => 22)/Blank', '(if Buyer Type= Multi Family Buyer => sample,sample1)/Blank', '(if Buyer Type= Multi Family Buyer => yes/no)/Blank',

            'sample,sample1', '100','200','1 (Max 3)','2 (Max 3)', '(if Property Type == Land) sample1,sample2/Blank','(if Property Type == Land) sample/Blank','(if Property Type == Land) sample/Blank','[On Market,Off Market,No Preference]','[Email,Text,Call,No Preference]'
            ]
        ];
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */

     public function headings(): array
     {
         return [
            __('cruds.buyer.fields.first_name'),
            __('cruds.buyer.fields.last_name'),
            __('cruds.buyer.fields.email'),
            __('cruds.buyer.fields.phone'),
            __('cruds.buyer.fields.address'),
            __('cruds.buyer.fields.city'),
            __('cruds.buyer.fields.state'),
            __('cruds.buyer.fields.zip_code'),
            __('cruds.buyer.fields.company_name'),
            __('cruds.buyer.fields.occupation'),
            __('cruds.buyer.fields.replacing_occupation'),
            __('cruds.buyer.fields.bedroom_min'),
            __('cruds.buyer.fields.bedroom_max'),
            __('cruds.buyer.fields.bath_min'),
            __('cruds.buyer.fields.bath_max'),
            __('cruds.buyer.fields.size_min'),
            __('cruds.buyer.fields.size_max'),
            __('cruds.buyer.fields.lot_size_min'),
            __('cruds.buyer.fields.lot_size_max'),
            __('cruds.buyer.fields.build_year_min'),
            __('cruds.buyer.fields.build_year_max'),
            __('cruds.buyer.fields.arv_min'),
            __('cruds.buyer.fields.arv_max'),
            __('cruds.buyer.fields.parking'),
            __('cruds.buyer.fields.property_type'),
            __('cruds.buyer.fields.property_flaw'),
            __('cruds.buyer.fields.solar'),
            __('cruds.buyer.fields.pool'),
            __('cruds.buyer.fields.septic'),
            __('cruds.buyer.fields.well'),
            __('cruds.buyer.fields.age_restriction'),
            __('cruds.buyer.fields.rental_restriction'),
            __('cruds.buyer.fields.hoa'),
            __('cruds.buyer.fields.tenant'),
            __('cruds.buyer.fields.post_possession'),
            __('cruds.buyer.fields.building_required'),
            __('cruds.buyer.fields.foundation_issues'),
            __('cruds.buyer.fields.mold'),
            __('cruds.buyer.fields.fire_damaged'),
            __('cruds.buyer.fields.rebuild'),
            __('cruds.buyer.fields.buyer_type'),
            __('cruds.buyer.fields.max_down_payment_percentage'),
            __('cruds.buyer.fields.max_down_payment_money'),
            __('cruds.buyer.fields.max_interest_rate'),
            __('cruds.buyer.fields.balloon_payment'),
            __('cruds.buyer.fields.unit_min'),
            __('cruds.buyer.fields.unit_max'),
            __('cruds.buyer.fields.building_class'),
            __('cruds.buyer.fields.value_add'),
            __('cruds.buyer.fields.purchase_method'),
            __('cruds.buyer.fields.price_min'),
            __('cruds.buyer.fields.price_max'),
            __('cruds.buyer.fields.of_stories_min'),
            __('cruds.buyer.fields.of_stories_max'),
            __('cruds.buyer.fields.zoning'),
            __('cruds.buyer.fields.utilities'),
            __('cruds.buyer.fields.sewer'),
            __('cruds.buyer.fields.market_preferance'),
            __('cruds.buyer.fields.contact_preferance'),

         ];
     }
}
