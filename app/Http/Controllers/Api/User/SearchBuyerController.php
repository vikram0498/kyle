<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Buyer;
use App\Models\UserBuyerLikes;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchBuyersRequest;
use Illuminate\Support\Facades\Cache;
use App\Models\BuyerPlan;


class SearchBuyerController extends Controller
{
    public function searchBuyerFormElementValues(){
        $elementValues = [];
        try{

            if (Cache::has('searchFormElementDetails')){
                $responseData = [
                    'status'        => true,
                    'result'        => Cache::get('searchFormElementDetails'),
                ];
                return response()->json($responseData, 200);
            }else{

                $elementValues['market_preferances'] = collect(config('constants.market_preferances'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => $label,
                    ];
                })->values()->forget(2)->all();
                
                $elementValues['property_types'] = collect(config('constants.property_types'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => $label,
                    ];
                })->values()->all();

                $elementValues['park'] = collect(config('constants.park'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => $label,
                    ];
                })->values()->all();

                $elementValues['building_class_values'] = collect(config('constants.building_class_values'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => ucwords(strtolower($label)),
                    ];
                })->values()->all();

                $elementValues['purchase_methods'] = collect(config('constants.purchase_methods'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => ucwords(strtolower($label)),
                    ];
                })->values()->all();

                $elementValues['parking_values'] = collect(config('constants.parking_values'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => ucwords(strtolower($label)),
                    ];
                })->values()->all();

                $elementValues['location_flaws'] = collect(config('constants.property_flaws'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => ucwords(strtolower($label)),
                    ];
                })->values()->all();

                $elementValues['buyer_types'] = collect(config('constants.buyer_types'))->map(function ($label, $value) {
                    if(in_array($value,array(5,11))){
                        return [
                            'value' => $value,
                            'label' => ucwords(strtolower($label)),
                        ];
                    }
                })->whereNotNull('value')->values()->all();

                $elementValues['zonings'] = collect(config('constants.zonings'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => ucwords(strtolower($label)),
                    ];
                })->values()->all();

                $elementValues['utilities'] = collect(config('constants.utilities'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => ucwords(strtolower($label)),
                    ];
                })->values()->all();

                $elementValues['sewers'] = collect(config('constants.sewers'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => ucwords(strtolower($label)),
                    ];
                })->values()->all();

                $elementValues['contact_preferances'] = collect(config('constants.contact_preferances'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => ucwords(strtolower($label)),
                    ];
                })->values()->all();

                $states = DB::table('states')->where('country_id',233)->where('flag','=',1)->orderBy('name','ASC')->pluck('name','id');

                $elementValues['states'] = $states->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => ucwords(strtolower($label)),
                    ];
                })->values()->all();

                Cache::put('searchFormElementDetails',$elementValues);

                //Return Sucess Response
                $responseData = [
                    'status'        => true,
                    'result'        => $elementValues,
                ];
                return response()->json($responseData, 200);
            }

        } catch (\Exception $e) {
            // dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'result'        => $elementValues,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 400);

        }
    }

    public function buyBoxSearch(SearchBuyersRequest $request){

        $radioValues = [0,1];
        DB::beginTransaction();
        try {
           
            $userId = auth()->user()->id;
            
            // $buyers = Buyer::query()->select('id','user_id','contact_preferance','created_by');

            $buyers = Buyer::select(['buyers.id', 'buyers.user_id', 'buyers.buyer_user_id', 'buyers.created_by', 'buyers.contact_preferance', 'buyer_plans.position as plan_position', 'buyers.is_profile_verified', 'buyers.plan_id'])->leftJoin('buyer_plans', 'buyer_plans.id', '=', 'buyers.plan_id');


            $additionalBuyers = Buyer::query();

            if($request->activeTab){
                if($request->activeTab == 'my_buyers'){
                    $buyers = $buyers->whereRelation('buyersPurchasedByUser', 'user_id', '=', $userId);
                }elseif($request->activeTab == 'more_buyers'){
                    $buyers = $buyers->whereDoesntHave('buyersPurchasedByUser', function ($query) use($userId) {
                        $query->where('user_id', '=',$userId);
                    })->where('user_id', '=', 1);

                    $additionalBuyers->whereDoesntHave('buyersPurchasedByUser', function ($query) use($userId) {
                        $query->where('user_id', '=',$userId);
                    })->where('user_id', '=', 1);
                }
            }

            $buyers = $buyers->where('buyers.status', 1);
        
            if($request->property_type){
                $selectedValues = [$request->property_type];

                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("property_type", (int)$value);
                    }
                });

                $additionalBuyers = $additionalBuyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("property_type", (int)$value);
                    }
                });
            }

            if($request->park){
                $parkType = (int)$request->park;
                $buyers = $buyers->where('park', $parkType);
                $additionalBuyers = $additionalBuyers->where('park', $parkType);
            }

            if($request->address){
                $buyers = $buyers->where('address', 'like', '%'.$request->address.'%');
                $additionalBuyers = $additionalBuyers->where('address', 'like', '%'.$request->address.'%');
            }

            // if($request->country){
            //     $country =  DB::table('countries')->where('id',$request->country)->value('name');
            //     $buyers = $buyers->where('country', $country);
            //     $additionalBuyers = $additionalBuyers->where('country', $country);
            // }

            if($request->state){
                $selectedValues = $request->state;
                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("state", $value);
                    }
                });

                $additionalBuyers = $additionalBuyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("state", $value);
                    }
                });
            }

            if($request->city){
                $selectedValues = $request->city;
                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("city", $value);
                    }
                });

                $additionalBuyers = $additionalBuyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("city", $value);
                    }
                });
            }

            if($request->zip_code){
                $buyers = $buyers->where('zip_code', $request->zip_code);
                $additionalBuyers = $additionalBuyers->where('zip_code', $request->zip_code);
            }

            if($request->price){
                $priceValue = $request->price;
                $buyers = $buyers->where(function ($query) use ($priceValue) {
                    $query->where('price_min', '<=', $priceValue)
                          ->where('price_max', '>=', $priceValue);
                });
                $additionalBuyers = $additionalBuyers->where(function ($query) use ($priceValue) {
                    $query->where('price_min', '<=', $priceValue)
                          ->where('price_max', '>=', $priceValue);
                });
            } 

            if($request->bedroom && is_numeric($request->bedroom)){
                $bedroomValue = $request->bedroom;
                $buyers = $buyers->where(function ($query) use ($bedroomValue) {
                    $query->where('bedroom_min', '<=', $bedroomValue)
                          ->where('bedroom_max', '>=', $bedroomValue);
                });
                $additionalBuyers = $additionalBuyers->where(function ($query) use ($bedroomValue) {
                    $query->where('bedroom_min', '<=', $bedroomValue)
                          ->where('bedroom_max', '>=', $bedroomValue);
                });
            } 

            if($request->bath && is_numeric($request->bath)){
                $bathValue = $request->bath;
                $buyers = $buyers->where(function ($query) use ($bathValue) {
                    $query->where('bath_min', '<=', $bathValue)
                          ->where('bath_max', '>=', $bathValue);
                });
                $additionalBuyers = $additionalBuyers->where(function ($query) use ($bathValue) {
                    $query->where('bath_min', '<=', $bathValue)
                          ->where('bath_max', '>=', $bathValue);
                });
            }

            // if($request->rooms && is_numeric($request->rooms)){
            //     $roomsValue = $request->rooms;
            //     $buyers = $buyers->where(function ($query) use ($roomsValue) {
            //         $query->where('rooms', '<=', $roomsValue)
            //               ->where('rooms', '>=', $roomsValue);
            //     });
            //     $additionalBuyers = $additionalBuyers->where(function ($query) use ($roomsValue) {
            //         $query->where('rooms', '<=', $roomsValue)
            //               ->where('rooms', '>=', $roomsValue);
            //     });
            // } 

            if($request->permanent_affix && is_numeric($request->permanent_affix)){
                $permanent_affixValue = (int)$request->permanent_affix;
                $buyers = $buyers->where('permanent_affix',$permanent_affixValue);
                $additionalBuyers = $additionalBuyers->where('permanent_affix',$permanent_affixValue);
            }

            if($request->size && is_numeric($request->size)){
                $sizeValue = $request->size;
                $buyers = $buyers->where(function ($query) use ($sizeValue) {
                    $query->where('size_min', '<=', $sizeValue)
                          ->where('size_max', '>=', $sizeValue);
                });
                $additionalBuyers = $additionalBuyers->where(function ($query) use ($sizeValue) {
                    $query->where('size_min', '<=', $sizeValue)
                          ->where('size_max', '>=', $sizeValue);
                });
            } 

            if($request->lot_size && is_numeric($request->lot_size)){
                $lotSizeValue = $request->lot_size;
                $buyers = $buyers->where(function ($query) use ($lotSizeValue) {
                    $query->where('lot_size_min', '<=', $lotSizeValue)
                          ->where('lot_size_max', '>=', $lotSizeValue);
                });
                $additionalBuyers = $additionalBuyers->where(function ($query) use ($lotSizeValue) {
                    $query->where('lot_size_min', '<=', $lotSizeValue)
                          ->where('lot_size_max', '>=', $lotSizeValue);
                });
            } 
            
            if($request->build_year && is_numeric($request->build_year)){
                $buildYearValue = $request->build_year;
                $buyers = $buyers->where(function ($query) use ($buildYearValue) {
                    $query->where('build_year_min', '<=', $buildYearValue)
                          ->where('build_year_max', '>=', $buildYearValue);
                });
                $additionalBuyers = $additionalBuyers->where(function ($query) use ($buildYearValue) {
                    $query->where('build_year_min', '<=', $buildYearValue)
                          ->where('build_year_max', '>=', $buildYearValue);
                });
            }

            if($request->arv && is_numeric($request->arv)){
                $arvValue = $request->arv;
                $buyers = $buyers->where(function ($query) use ($arvValue) {
                    $query->where('arv_min', '<=', $arvValue)
                          ->where('arv_max', '>=', $arvValue);
                });
                $additionalBuyers = $additionalBuyers->where(function ($query) use ($arvValue) {
                    $query->where('arv_min', '<=', $arvValue)
                          ->where('arv_max', '>=', $arvValue);
                });
            }

            if($request->parking){
                $buyers = $buyers->where('parking', $request->parking);
                $additionalBuyers = $additionalBuyers->where('parking', $request->parking);
            }

            if($request->property_flaw){
                $selectedValues = $request->property_flaw;

                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("property_flaw", $value);
                    }
                });

                $additionalBuyers = $additionalBuyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("property_flaw", $value);
                    }
                });
            }

            if($request->purchase_method){
                $selectedValues = $request->purchase_method;

                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("purchase_method", $value);
                    }
                });
                
                $additionalBuyers = $additionalBuyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("purchase_method", $value);
                    }
                });
              
            }

            if($request->zoning){
                $selectedValues = $request->zoning;

                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("zoning", $value);
                    }
                });

                $additionalBuyers = $additionalBuyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("zoning", $value);
                    }
                });
            }

            if($request->utilities){
                $buyers = $buyers->where('utilities', $request->utilities);
                $additionalBuyers = $additionalBuyers->where('utilities', $request->utilities);
            }

            if($request->sewer){
                $buyers = $buyers->where('sewer', $request->sewer);
                $additionalBuyers = $additionalBuyers->where('sewer', $request->sewer);
            }
            
            if($request->market_preferance){
                $marketPref = $request->market_preferance;
                $buyers = $buyers->where(function($query) use($marketPref){
                    $query->where('market_preferance', $marketPref)->orWhere('market_preferance',3);
                });
                $additionalBuyers = $additionalBuyers->where(function($query) use($marketPref){
                    $query->where('market_preferance', $marketPref)->orWhere('market_preferance',3);
                });
            }

            // if($request->contact_preferance){
            //     $buyers = $buyers->where('contact_preferance', $request->contact_preferance);
            //     $additionalBuyers = $additionalBuyers->where('contact_preferance', $request->contact_preferance);
            // }

         

            if($request->stories && is_numeric($request->stories)){
                $stories_value = $request->stories;
                $buyers = $buyers->where(function ($query) use ($stories_value) {
                    $query->where('stories_min', '<=', $stories_value)
                          ->where('stories_max', '>=', $stories_value);
                });
                $additionalBuyers = $additionalBuyers->where(function ($query) use ($stories_value) {
                    $query->where('stories_min', '<=', $stories_value)
                          ->where('stories_max', '>=', $stories_value);
                });
            } 


            if(!is_null($request->solar) && in_array($request->solar, $radioValues)){
                $buyers = $buyers->where('solar', $request->solar);
                $additionalBuyers = $additionalBuyers->where('solar', $request->solar);
            }
            
            if(!is_null($request->permanent_affix) && in_array($request->permanent_affix, $radioValues)){
                $buyers = $buyers->where('permanent_affix', $request->permanent_affix);
                $additionalBuyers = $additionalBuyers->where('permanent_affix', $request->permanent_affix);
            }

            if(!is_null($request->pool) && in_array($request->pool, $radioValues)){
                $buyers = $buyers->where('pool', $request->pool);
                $additionalBuyers = $additionalBuyers->where('pool', $request->pool);
            }

            if(!is_null($request->septic) && in_array($request->septic, $radioValues)){
                $buyers = $buyers->where('septic', $request->septic);
                $additionalBuyers = $additionalBuyers->where('septic', $request->septic);
            }

            if(!is_null($request->well) && in_array($request->well, $radioValues)){
                $buyers = $buyers->where('well', $request->well);
                $additionalBuyers = $additionalBuyers->where('well', $request->well);
            }

            if(!is_null($request->age_restriction) && in_array($request->age_restriction, $radioValues)){
                $buyers = $buyers->where('age_restriction', $request->age_restriction);
                $additionalBuyers = $additionalBuyers->where('age_restriction', $request->age_restriction);
            }

            if(!is_null($request->rental_restriction) && in_array($request->rental_restriction, $radioValues)){
                $buyers = $buyers->where('rental_restriction', $request->rental_restriction);
                $additionalBuyers = $additionalBuyers->where('rental_restriction', $request->rental_restriction);
            }

            if(!is_null($request->hoa) && in_array($request->hoa, $radioValues)){
                $buyers = $buyers->where('hoa', $request->hoa);
                $additionalBuyers = $additionalBuyers->where('hoa', $request->hoa);
            }

            if(!is_null($request->tenant) && in_array($request->tenant, $radioValues)){
                $buyers = $buyers->where('tenant', $request->tenant);
                $additionalBuyers = $additionalBuyers->where('tenant', $request->tenant);
            }

            if(!is_null($request->post_possession) && in_array($request->post_possession, $radioValues)){
                $buyers = $buyers->where('post_possession', $request->post_possession);
                $additionalBuyers = $additionalBuyers->where('post_possession', $request->post_possession);
            }

            if(!is_null($request->building_required) && in_array($request->building_required, $radioValues)){
                $buyers = $buyers->where('building_required', $request->building_required);
                $additionalBuyers = $additionalBuyers->where('building_required', $request->building_required);
            }

            if(!is_null($request->foundation_issues) && in_array($request->foundation_issues, $radioValues)){
                $buyers = $buyers->where('foundation_issues', $request->foundation_issues);
                $additionalBuyers = $additionalBuyers->where('foundation_issues', $request->foundation_issues);
            }

            if(!is_null($request->mold) && in_array($request->mold, $radioValues)){
                $buyers = $buyers->where('mold', $request->mold);
                $additionalBuyers = $additionalBuyers->where('mold', $request->mold);
            }

            if(!is_null($request->fire_damaged) && in_array($request->fire_damaged, $radioValues)){
                $buyers = $buyers->where('fire_damaged', $request->fire_damaged);
                $additionalBuyers = $additionalBuyers->where('fire_damaged', $request->fire_damaged);
            }

            if(!is_null($request->rebuild) && in_array($request->rebuild, $radioValues)){
                $buyers = $buyers->where('rebuild', $request->rebuild);
                $additionalBuyers = $additionalBuyers->where('rebuild', $request->rebuild);
            }

            if(!is_null($request->squatters) && in_array($request->squatters, $radioValues)){
                $buyers = $buyers->where('squatters', $request->squatters);
                $additionalBuyers = $additionalBuyers->where('squatters', $request->squatters);
            }
            
            if($request->total_units){
                $buyers = $buyers->where('unit_min', '<=', $request->total_units)->where('unit_max' ,'>=',$request->total_units);
                $additionalBuyers = $additionalBuyers->where('unit_min', '<=', $request->total_units)->where('unit_max' ,'>=',$request->total_units);
            }

            if($request->max_down_payment_percentage){
                $buyers = $buyers->where('max_down_payment_percentage', $request->max_down_payment_percentage);
                $additionalBuyers = $additionalBuyers->where('max_down_payment_percentage', $request->max_down_payment_percentage);
            }

            if($request->max_down_payment_money){
                $buyers = $buyers->where('max_down_payment_money', $request->max_down_payment_money);
                $additionalBuyers = $additionalBuyers->where('max_down_payment_money', $request->max_down_payment_money);
            }

            if($request->max_interest_rate){
                $buyers = $buyers->where('max_interest_rate', $request->max_interest_rate);
                $additionalBuyers = $additionalBuyers->where('max_interest_rate', $request->max_interest_rate);
            }

            if(!is_null($request->balloon_payment) && in_array($request->balloon_payment, $radioValues)){
                $buyers = $buyers->where('balloon_payment', $request->balloon_payment);
                $additionalBuyers = $additionalBuyers->where('balloon_payment', $request->balloon_payment);
            }

            if($request->building_class){
                $selectedValues = [$request->building_class];
                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("building_class", (int)$value);
                    }
                });

                $additionalBuyers = $additionalBuyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("building_class", (int)$value);
                    }
                });
            }

            if(!is_null($request->value_add) && in_array($request->value_add, $radioValues)){
                $buyers = $buyers->where('value_add', $request->value_add);
                $additionalBuyers = $additionalBuyers->where('value_add', $request->value_add);
            }

            if($request->buyer_type){
                $buyers = $buyers->where('buyer_type', $request->buyer_type);
                $additionalBuyers = $additionalBuyers->where('buyer_type', $request->buyer_type);
            }
           
            $totalRecord = $buyers->count();
            
            $authUserLevelType = auth()->user()->level_type;

            $pagination = 20;
            if($authUserLevelType == 2 && $request->activeTab == 'more_buyers'){
                $pagination = 50;
            }elseif($authUserLevelType == 3 && $request->activeTab == 'more_buyers'){
                $pagination = 50;
            }

            $buyers = $buyers
            // ->orderBy('created_by','desc');
            // ->orderBy(BuyerPlan::select('position')->whereColumn('buyer_plans.id', 'buyers.plan_id'), 'asc');
            ->orderByRaw('ISNULL(plan_position), plan_position ASC');
            
            $buyers = $buyers->paginate($pagination);

            // Get additional buyer
            $additionalBuyers = $additionalBuyers->whereDoesntHave('buyersPurchasedByUser', function ($query) use($userId) {
                $query->where('user_id', '=',$userId);
            })->where('user_id', '=', 1);

            $insertLogRecords = $request->all();
            $insertLogRecords['user_id'] = $userId;

            if(isset($request->filterType) && $request->filterType == 'search_page'){
                $insertLogRecords['country'] =  233;
                $insertLogRecords['state']   =  $request->state ?? null;
                $insertLogRecords['city']    =  $request->city ?? null;
                $insertLogRecords['permanent_affix'] =  (!is_null($request->permanent_affix))?$request->permanent_affix : 0;
                $insertLogRecords['park']    =  $request->park;
                $insertLogRecords['rooms']    =  $request->rooms;
                $insertLogRecords['zoning']  =  ($request->zoning && count($request->zoning) > 0) ? json_encode($request->zoning) : null;
                SearchLog::create($insertLogRecords);
            }
            
            foreach ($buyers as $key=>$buyer){
                $liked = false;
                $disliked = false;
                
                $buyerDetails = $buyer->buyersPurchasedByUser()->first() ?  $buyer->buyersPurchasedByUser()->first()->buyer->userDetail : $buyer->userDetail;

                $name = $buyerDetails->name ?? '';

                // if(auth()->user()->is_buyer){
                //     $name = $buyer->userDetail->name;
                // }

                // if(auth()->user()->is_seller){
                //     $name = $buyer->seller->name;
                // }

                $getrecentaction=UserBuyerLikes::select('liked','disliked')->where('user_id',auth()->user()->id)->where('buyer_id',$buyer->id)->first();
                if($getrecentaction){
                    $liked = $getrecentaction->liked == 1 ? true : false;
                    $disliked = $getrecentaction->disliked == 1 ? true : false;
                }
                
                if($request->activeTab){
                    if($request->activeTab == 'my_buyers'){

                        if($buyerDetails){
                            $buyer->name =  ucwords($name);
                            $buyer->first_name = ucwords($buyerDetails->first_name);
                            $buyer->last_name = ucwords($buyerDetails->last_name);
                            $buyer->email = $buyerDetails->email;
                            $buyer->phone = $buyerDetails->phone;
                        }

                        $buyer->contact_preferance_id = $buyer->contact_preferance;
                        $buyer->contact_preferance = $buyer->contact_preferance ? config('constants.contact_preferances')[$buyer->contact_preferance]: '';
                        $buyer->redFlag = $buyer->redFlagedData()->where('user_id',$userId)->exists();
                        $buyer->totalBuyerLikes = totalLikes($buyer->id);
                        $buyer->totalBuyerUnlikes = totalUnlikes($buyer->id);
                        $buyer->redFlagShow = $buyer->buyersPurchasedByUser()->where('user_id',auth()->user()->id)->exists();
                        $buyer->createdByAdmin = ($buyer->created_by == 1) ? true : false;
                        $buyer->liked = $liked;
                        $buyer->disliked = $disliked;

                        $buyer->buyer_profile_image = $buyer->userDetail->profile_image_url ?? '';

                        $buyer->email_verified = $buyer->userDetail->email_verified_at != null ? true : false;
                        $buyer->phone_verified = $buyer->userDetail->phone_verified_at != null ? true : false;
                        $buyer->profile_tag_name = $buyer->buyerPlan ? $buyer->buyerPlan->title : null;
                        $buyer->profile_tag_image = $buyer->buyerPlan ? $buyer->buyerPlan->image_url : null;
                        $buyer->is_buyer_verified = $buyer->userDetail->is_buyer_verified;

                        //Start Verification status
                        $buyer->is_phone_verified = $buyer->userDetail->buyerVerification->is_phone_verification ? true : false;

                        $buyer->driver_license_verified = $buyer->userDetail->buyerVerification->driver_license_status == 'verified' ? true : false;

                        $buyer->proof_of_funds_verified = $buyer->userDetail->buyerVerification->proof_of_funds_status == 'verified' ? true : false;

                        $buyer->llc_verified = $buyer->userDetail->buyerVerification->llc_verification_status == 'verified' ? true : false;

                        $buyer->is_application_verified = $buyer->userDetail->buyerVerification->is_application_process ? true : false;
                        //End Verification status


                    }else if($request->activeTab == 'more_buyers'){
                        // $buyer->user = $buyer->user_id;
                        if($buyerDetails){
                            $buyer->first_name  =  substr($buyerDetails->first_name, 0, 1).str_repeat("X", strlen($buyerDetails->first_name)-1);
                            $buyer->last_name  =  substr($buyerDetails->last_name, 0, 1).str_repeat("X", strlen($buyerDetails->last_name)-1);
                            $buyer->name  =  substr($name, 0, 3).str_repeat("X", strlen($name)-3);
                            $buyer->email =  substr($buyerDetails->email, 0, 3).str_repeat("X", strlen($buyerDetails->email)-3);
                            $buyer->phone =  substr($buyerDetails->phone, 0, 3).str_repeat("X", strlen($buyerDetails->phone)-3);
                        }

                        $contactPreference = $buyer->contact_preferance ? config('constants.contact_preferances')[$buyer->contact_preferance]: '';
                        
                        $buyer->contact_preferance_id = $buyer->contact_preferance;

                        $buyer->contact_preferance = substr($contactPreference, 0, 1).str_repeat("X", strlen($contactPreference)-1);

                        $buyer->redFlag = $buyer->redFlagedData()->where('user_id',$userId)->exists();
                        $buyer->totalBuyerLikes = totalLikes($buyer->id);
                        $buyer->totalBuyerUnlikes = totalUnlikes($buyer->id);
                        $buyer->redFlagShow = $buyer->buyersPurchasedByUser()->where('user_id',auth()->user()->id)->exists();
                        $buyer->createdByAdmin = ($buyer->created_by == 1) ? true : false;
                        $buyer->liked = $liked;
                        $buyer->disliked = $disliked;

                        // $buyer->buyer_profile_image = $buyer->userDetail->profile_image_url ?? '';

                        $buyer->email_verified = $buyer->userDetail->email_verified_at != null ? true : false;
                        $buyer->phone_verified = $buyer->userDetail->phone_verified_at != null ? true : false;
                        $buyer->profile_tag_name = $buyer->buyerPlan ? $buyer->buyerPlan->title : null;
                        $buyer->profile_tag_image = $buyer->buyerPlan ? $buyer->buyerPlan->image_url : null;
                        $buyer->is_buyer_verified = $buyer->userDetail->is_buyer_verified;


                        //Start Verification status
                        $buyer->driver_license_verified = $buyer->userDetail->buyerVerification->driver_license_status == 'verified' ? true : false;
 
                        $buyer->proof_of_funds_verified = $buyer->userDetail->buyerVerification->proof_of_funds_status == 'verified' ? true : false;
 
                        $buyer->llc_verified = $buyer->userDetail->buyerVerification->llc_verification_status == 'verified' ? true : false;
 
                        $buyer->is_application_verified = $buyer->userDetail->buyerVerification->is_application_process ? true : false;
                        //End Verification status
                    }
                }
            }
            
            //Return Success Response
            $responseData = [
                'status'        => true,
                'buyers'        => $buyers,
                'activeTab'     => $request->activeTab,
                'additional_buyers_count' => $additionalBuyers->count(),
                'total_records' => $totalRecord
            ];

            DB::commit();

            return response()->json($responseData, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
                'error_details' => $e->getMessage().'->'.$e->getLine(),
            ];
            return response()->json($responseData, 400);
        }

    }

    public function lastSearchBuyers(){
        try {
            $radioValues = [0,1];
            $userId = auth()->user()->id;
            $lastSearchLog = SearchLog::where('user_id',$userId)->orderBy('id','desc')->first();
            
            if($lastSearchLog){

            // $buyers = Buyer::query()->with(['userDetail'])->select('id','user_id','created_by','contact_preferance')->where('status', 1)->whereRelation('buyersPurchasedByUser', 'user_id', '=', $userId);

            $buyers = Buyer::select(['buyers.id', 'buyers.user_id','buyers.buyer_user_id', 'buyers.created_by', 'buyers.contact_preferance', 'buyer_plans.position as plan_position', 'buyers.is_profile_verified', 'buyers.plan_id','buyers.status'])->leftJoin('buyer_plans', 'buyer_plans.id', '=', 'buyers.plan_id')->where('buyers.status', 1)->whereRelation('buyersPurchasedByUser', 'user_id', '=', $userId);

            
            if($lastSearchLog->property_type){
                $selectedValues = [$lastSearchLog->property_type];

                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("property_type", (int)$value);
                    }
                });

            }

            if($lastSearchLog->address){
                $buyers = $buyers->where('address', 'like', '%'.$lastSearchLog->address.'%');
            }

            // if($lastSearchLog->country){
            //     $buyers = $buyers->where('country', $lastSearchLog->country);
            // }

            if($lastSearchLog->state){
                $selectedValues = $lastSearchLog->state;
                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("state", $value);
                    }
                });
            }

            if($lastSearchLog->city){
                $selectedValues = $lastSearchLog->city;
                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("city", $value);
                    }
                });
            }

            if($lastSearchLog->zip_code){
                $buyers = $buyers->where('zip_code', $lastSearchLog->zip_code);
            }

            if($lastSearchLog->price){
                $priceValue = $lastSearchLog->price;
                $buyers = $buyers->where(function ($query) use ($priceValue) {
                    $query->where('price_min', '<=', $priceValue)
                          ->where('price_max', '>=', $priceValue);
                });
                $additionalBuyers = $buyers->where(function ($query) use ($priceValue) {
                    $query->where('price_min', '<=', $priceValue)
                          ->where('price_max', '>=', $priceValue);
                });
            } 

            if($lastSearchLog->bedroom && is_numeric($lastSearchLog->bedroom)){
                $bedroomValue = $lastSearchLog->bedroom;
                $buyers = $buyers->where(function ($query) use ($bedroomValue) {
                    $query->where('bedroom_min', '<=', $bedroomValue)
                          ->where('bedroom_max', '>=', $bedroomValue);
                });
            } 

            if($lastSearchLog->bath && is_numeric($lastSearchLog->bath)){
                $bathValue = $lastSearchLog->bath;
                $buyers = $buyers->where(function ($query) use ($bathValue) {
                    $query->where('bath_min', '<=', $bathValue)
                          ->where('bath_max', '>=', $bathValue);
                });
            } 

            if($lastSearchLog->size && is_numeric($lastSearchLog->size)){
                $sizeValue = $lastSearchLog->size;
                $buyers = $buyers->where(function ($query) use ($sizeValue) {
                    $query->where('size_min', '<=', $sizeValue)
                          ->where('size_max', '>=', $sizeValue);
                });
            } 

            if($lastSearchLog->lot_size && is_numeric($lastSearchLog->lot_size)){
                $lotSizeValue = $lastSearchLog->lot_size;
                $buyers = $buyers->where(function ($query) use ($lotSizeValue) {
                    $query->where('lot_size_min', '<=', $lotSizeValue)
                          ->where('lot_size_max', '>=', $lotSizeValue);
                });
            } 
            
            if($lastSearchLog->build_year && is_numeric($lastSearchLog->build_year)){
                $buildYearValue = $lastSearchLog->build_year;
                $buyers = $buyers->where(function ($query) use ($buildYearValue) {
                    $query->where('build_year_min', '<=', $buildYearValue)
                          ->where('build_year_max', '>=', $buildYearValue);
                });
            }

            // if($lastSearchLog->arv && is_numeric($lastSearchLog->arv)){
            //     $arvValue = $lastSearchLog->arv;
            //     $buyers = $buyers->where(function ($query) use ($arvValue) {
            //         $query->where('arv_min', '<=', $arvValue)
            //               ->where('arv_max', '>=', $arvValue);
            //     });
            // }

            if($lastSearchLog->parking){
                $buyers = $buyers->whereJsonContains('parking', intval($lastSearchLog->parking));
            }

            if($lastSearchLog->property_flaw){
                $selectedValues = $lastSearchLog->property_flaw;
                
                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("property_flaw", $value);
                    }
                });
            }

            if($lastSearchLog->purchase_method){
                $selectedValues = $lastSearchLog->purchase_method;

                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("purchase_method", $value);
                    }
                });
            }

            if($lastSearchLog->zoning){
                $selectedValues = $lastSearchLog->zoning;

                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("zoning", $value);
                    }
                });
            }

            if($lastSearchLog->utilities){
                $buyers = $buyers->where('utilities', $lastSearchLog->utilities);
            }

            if($lastSearchLog->sewer){
                $buyers = $buyers->where('sewer', $lastSearchLog->sewer);
            }

            if($lastSearchLog->market_preferance){
                $marketPref = $lastSearchLog->market_preferance;
                $buyers = $buyers->where(function($query) use($marketPref){
                    $query->where('market_preferance', $marketPref)->orWhere('market_preferance',3);
                });
            }

            if($lastSearchLog->contact_preferance){
                $buyers = $buyers->where('contact_preferance',$lastSearchLog->contact_preferance);
            }

            if($lastSearchLog->building_class){
                $selectedValues = [$lastSearchLog->building_class];
                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("building_class", (int)$value);
                    }
                });

            } 


            if($lastSearchLog->stories && is_numeric($lastSearchLog->stories)){
                $stories_value = $lastSearchLog->stories;
                $buyers = $buyers->where(function ($query) use ($stories_value) {
                    $query->where('stories_min', '<=', $stories_value)
                          ->where('stories_max', '>=', $stories_value);
                });
            } 

            if(!is_null($lastSearchLog->solar) && in_array($lastSearchLog->solar, $radioValues)){
                $buyers = $buyers->where('solar', $lastSearchLog->solar);
            }

            if(!is_null($lastSearchLog->pool) && in_array($lastSearchLog->pool, $radioValues)){
                $buyers = $buyers->where('pool', $lastSearchLog->pool);
            }

            if(!is_null($lastSearchLog->septic) && in_array($lastSearchLog->septic, $radioValues)){
                $buyers = $buyers->where('septic', $lastSearchLog->septic);
            }

            if(!is_null($lastSearchLog->well) && in_array($lastSearchLog->well, $radioValues)){
                $buyers = $buyers->where('well', $lastSearchLog->well);
            }

            if(!is_null($lastSearchLog->age_restriction) && in_array($lastSearchLog->age_restriction, $radioValues)){
                $buyers = $buyers->where('age_restriction', $lastSearchLog->age_restriction);
            }

            if(!is_null($lastSearchLog->rental_restriction) && in_array($lastSearchLog->rental_restriction, $radioValues)){
                $buyers = $buyers->where('rental_restriction', $lastSearchLog->rental_restriction);
            }

            if(!is_null($lastSearchLog->hoa) && in_array($lastSearchLog->hoa, $radioValues)){
                $buyers = $buyers->where('hoa', $lastSearchLog->hoa);
            }

            if(!is_null($lastSearchLog->tenant) && in_array($lastSearchLog->tenant, $radioValues)){
                $buyers = $buyers->where('tenant', $lastSearchLog->tenant);
            }

            if(!is_null($lastSearchLog->post_possession) && in_array($lastSearchLog->post_possession, $radioValues)){
                $buyers = $buyers->where('post_possession', $lastSearchLog->post_possession);
            }

            if(!is_null($lastSearchLog->building_required) && in_array($lastSearchLog->building_required, $radioValues)){
                $buyers = $buyers->where('building_required', $lastSearchLog->building_required);
            }

            if(!is_null($lastSearchLog->foundation_issues) && in_array($lastSearchLog->foundation_issues, $radioValues)){
                $buyers = $buyers->where('foundation_issues', $lastSearchLog->foundation_issues);
            }

            if(!is_null($lastSearchLog->mold) && in_array($lastSearchLog->mold, $radioValues)){
                $buyers = $buyers->where('mold', $lastSearchLog->mold);
            }

            if(!is_null($lastSearchLog->fire_damaged) && in_array($lastSearchLog->fire_damaged, $radioValues)){
                $buyers = $buyers->where('fire_damaged', $lastSearchLog->fire_damaged);
            }

            if(!is_null($lastSearchLog->rebuild) && in_array($lastSearchLog->rebuild, $radioValues)){
                $buyers = $buyers->where('rebuild', $lastSearchLog->rebuild);
            }

            if(!is_null($lastSearchLog->squatters) && in_array($lastSearchLog->squatters, $radioValues)){
                $buyers = $buyers->where('squatters', $lastSearchLog->squatters);
            }
             
            if($lastSearchLog->total_units){
                $buyers = $buyers->where('unit_min', '<=', $lastSearchLog->total_units)->where('unit_max' ,'>=',$lastSearchLog->total_units);
            }

            if($lastSearchLog->max_down_payment_percentage){
                $buyers = $buyers->where('max_down_payment_percentage', $lastSearchLog->max_down_payment_percentage);
            }

            if($lastSearchLog->max_down_payment_money){
                $buyers = $buyers->where('max_down_payment_money', $lastSearchLog->max_down_payment_money);
            }

            if($lastSearchLog->max_interest_rate){
                $buyers = $buyers->where('max_interest_rate', $lastSearchLog->max_interest_rate);
            }

            if(!is_null($lastSearchLog->balloon_payment) && in_array($lastSearchLog->balloon_payment, $radioValues)){
                $buyers = $buyers->where('balloon_payment', $lastSearchLog->balloon_payment);
            }

            if($lastSearchLog->building_class){
                $buyers = $buyers->whereJsonContains('building_class', intval($lastSearchLog->building_class));
            }

            if(!is_null($lastSearchLog->value_add) && in_array($lastSearchLog->value_add, $radioValues)){
                $buyers = $buyers->where('value_add', $lastSearchLog->value_add);
            }
            
            if($lastSearchLog->permanent_affix && is_numeric($lastSearchLog->permanent_affix)){
                $permanent_affixValue = (int)$lastSearchLog->permanent_affix;
                $buyers = $buyers->where('permanent_affix',$permanent_affixValue);
            }


            $buyers = $buyers
                    // ->orderBy('created_by','desc')
                    // ->orderBy(BuyerPlan::select('position')->whereColumn('buyer_plans.id', 'buyers.plan_id'), 'asc')
                    ->orderByRaw('ISNULL(plan_position), plan_position ASC')
                    ->paginate(20);

            foreach ($buyers as $key=>$buyer){
                $liked=false;
                $disliked=false;
                
                $getrecentaction=UserBuyerLikes::select('liked','disliked')->where('user_id',$userId)->where('buyer_id',$buyer->id)->first();
                if($getrecentaction){
                    $liked=$getrecentaction->liked == 1 ? true : false;
                    $disliked=$getrecentaction->disliked == 1 ? true : false;
                }
                
                // dd($buyer->buyersPurchasedByUser()->first()->buyer->userDetail);

                $buyerDetails = $buyer->buyersPurchasedByUser()->first()->buyer->userDetail;
                if($buyerDetails){
                    $name = $buyerDetails->first_name.' '.$buyerDetails->first_name;
                    $buyer->name =  $name;

                    $buyer->first_name = $buyerDetails->first_name;
                    $buyer->last_name = $buyerDetails->last_name;
                    $buyer->email = $buyerDetails->email;
                    $buyer->phone = $buyerDetails->phone;
                }

                // if(auth()->user()->is_seller){
                //     $name = $buyer->seller->first_name.' '.$buyer->seller->first_name;
                //     $buyer->name =  $name;

                //     $buyer->first_name = $buyer->seller->first_name;
                //     $buyer->last_name = $buyer->seller->last_name;
                //     $buyer->email = $buyer->seller->email;
                //     $buyer->phone = $buyer->seller->phone;
                // }

                $buyer->contact_preferance_id = $buyer->contact_preferance;

                $buyer->contact_preferance = $buyer->contact_preferance ? config('constants.contact_preferances')[$buyer->contact_preferance]: '';
                $buyer->redFlag = $buyer->redFlagedData()->where('user_id',$userId)->exists();
                $buyer->totalBuyerLikes = totalLikes($buyer->id);
                $buyer->totalBuyerUnlikes = totalUnlikes($buyer->id);
                $buyer->liked= $liked;
                $buyer->disliked= $disliked;                
                $buyer->redFlagShow = $buyer->buyersPurchasedByUser()->where('user_id',auth()->user()->id)->exists();
                $buyer->createdByAdmin = ($buyer->created_by == 1) ? true : false;

                $buyer->buyer_profile_image = $buyer->userDetail->profile_image_url ?? '';

                $buyer->email_verified = $buyer->userDetail->email_verified_at != null ? true : false;
                $buyer->phone_verified = $buyer->userDetail->phone_verified_at != null ? true : false;
                $buyer->profile_tag_name = $buyer->buyerPlan ? $buyer->buyerPlan->title : null;
                $buyer->profile_tag_image = $buyer->buyerPlan ? $buyer->buyerPlan->image_url : null;
                $buyer->is_buyer_verified = $buyer->userDetail->is_buyer_verified;

                //Start Verification status
                $buyer->driver_license_verified = $buyer->userDetail->buyerVerification->driver_license_status == 'verified' ? true : false;

                $buyer->proof_of_funds_verified = $buyer->userDetail->buyerVerification->proof_of_funds_status == 'verified' ? true : false;

                $buyer->llc_verified = $buyer->userDetail->buyerVerification->llc_verification_status == 'verified' ? true : false;

                $buyer->is_application_verified = $buyer->userDetail->buyerVerification->is_application_process ? true : false;
                //End Verification status

            }
            
            //Return Success Response
            $responseData = [
                'status' => true,
                'buyers' => $buyers,
            ];

            return response()->json($responseData, 200);
         }else{
                //Return Error Response
                $responseData = [
                    'status'        => false,
                    'error'         => 'No Record Found!',
                ];
                return response()->json($responseData, 200);
         }
        }catch (\Exception $e) {
            // dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 400);
        }
    }

    public function lastSearchByUser(){
        $searchLog = SearchLog::select('address','country','state','city','zip_code','price','bedroom','bath','size','lot_size','build_year','arv','parking','property_type','property_flaw','solar','pool','septic','well','age_restriction','rental_restriction','hoa','tenant','post_possession','building_required','foundation_issues','mold','fire_damaged','rebuild','squatters','purchase_method','stories','zoning','utilities','sewer','market_preferance','contact_preferance','max_down_payment_percentage','max_down_payment_money','max_interest_rate','balloon_payment','total_units','unit_min','unit_max','building_class','value_add','status')->where('user_id',auth()->user()->id)->orderBy('id','desc')->first();

        //Success Response Send
         $responseData = [
            'status'   => true,
            'data'     => ['searchLog' => $searchLog]
        ];

        return response()->json($responseData, 200);
    }

}