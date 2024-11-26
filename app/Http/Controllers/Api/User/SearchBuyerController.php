<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Buyer;
use App\Models\UserBuyerLikes;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchBuyersRequest;
use App\Models\BuyerDeal;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;
use App\Mail\DealMail;
use Illuminate\Support\Facades\Mail;

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

        $radioValues = [1];
        try {
            DB::beginTransaction();
            $userId = auth()->user()->id;
            $authUserLevelType = auth()->user()->level_type;

            /*
            $buyers = Buyer::select(['buyers.id', 'buyers.user_id', 'buyers.buyer_user_id', 'buyers.created_by', 'buyers.contact_preferance', 'buyer_plans.position as plan_position', 'buyers.is_profile_verified', 'buyers.plan_id'])->leftJoin('buyer_plans', 'buyer_plans.id', '=', 'buyers.plan_id');
            */

            /** Update query 26-07-2024 */

            // Subquery to calculate verification count
            $verificationSubquery = DB::table('profile_verifications')
            ->select(DB::raw("   
                SUM(
                    CASE WHEN is_phone_verification = 1 THEN 1 ELSE 0 END +
                    CASE WHEN is_driver_license = 1 AND driver_license_status = 'verified' THEN 1 ELSE 0 END +
                    CASE WHEN is_proof_of_funds = 1 AND proof_of_funds_status = 'verified' THEN 1 ELSE 0 END +
                    CASE WHEN is_llc_verification = 1 AND llc_verification_status = 'verified' THEN 1 ELSE 0 END +
                    CASE WHEN is_certified_closer = 1 AND certified_closer_status = 'verified' THEN 1 ELSE 0 END +                    
                    CASE WHEN is_application_process = 1 THEN 1 ELSE 0 END
                ) AS verification_count
            "))
            ->whereColumn('user_id', 'buyers.buyer_user_id')
            ->toSql();

            $buyers = Buyer::leftJoin('users', 'users.id', '=', 'buyers.buyer_user_id')->select(['buyers.id', 'buyers.user_id', 'buyers.buyer_user_id', 'buyers.created_by', 'buyers.contact_preferance', 'buyer_plans.position as plan_position', 'buyers.is_profile_verified', 'buyers.plan_id','users.level_type',DB::raw("($verificationSubquery) as verification_count")])
            ->leftJoin('buyer_plans', 'buyer_plans.id', '=', 'buyers.plan_id');

            $additionalBuyers = Buyer::query();

            if($request->activeTab){
                if($request->activeTab == 'my_buyers'){
                    $buyers = $buyers->whereRelation('buyersPurchasedByUser', 'user_id', '=', $userId);
                }elseif($request->activeTab == 'more_buyers'){
                    
                     if(in_array($authUserLevelType, [1,2])){
                         $buyers = $buyers->whereDoesntHave('buyersPurchasedByUser', function ($query) use($userId) {
                            $query->where('user_id', '=',$userId);
                        })->where('user_id', '=', 1);
    
                        $additionalBuyers->whereDoesntHave('buyersPurchasedByUser', function ($query) use($userId) {
                            $query->where('user_id', '=',$userId);
                        })->where('user_id', '=', 1);
                     }else if($authUserLevelType == 3){
                         $buyers = $buyers->where('user_id', '!=', $userId);
                         $additionalBuyers->where('user_id', '!=', $userId);
                     }
                    
                    
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
            
            

           /* if($request->address){
                $buyers = $buyers->where('buyers.address', 'like', '%'.$request->address.'%');
                $additionalBuyers = $additionalBuyers->where('address', 'like', '%'.$request->address.'%');
            }*/

           if($request->state){
                $selectedValues = array_map('intval', $request->state);
            
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
                $selectedValues = array_map('intval', $request->city);
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

            /*
            if($request->state){
                $buyers = $buyers->where('state', $request->state);
                $additionalBuyers = $additionalBuyers->where('state', $request->state);
            }
            if($request->city){
                $buyers = $buyers->where('city', $request->city);
                $additionalBuyers = $additionalBuyers->where('city', $request->city);
            }
            */

            // if($request->zip_code){
            //     $buyers = $buyers->where('zip_code', $request->zip_code);
            //     $additionalBuyers = $additionalBuyers->where('zip_code', $request->zip_code);
            // }

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
                // $buyers = $buyers->where('parking', $request->parking);
                // $additionalBuyers = $additionalBuyers->where('parking', $request->parking);

                $selectedValues = [$request->parking];

                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("parking", (int)$value);
                    }
                });

                $additionalBuyers = $additionalBuyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("parking", (int)$value);
                    }
                });
            }

            
            
            if($request->property_flaw){
                // $selectedValues = $request->property_flaw;
                $propertyFlows = array_map('intval', $request->property_flaw);

                $buyers = $buyers->where(function ($query) use ($propertyFlows) {
                    foreach ($propertyFlows as $value) {
                        $query->orWhereJsonContains("property_flaw", $value);
                    }
                });

                $additionalBuyers = $additionalBuyers->where(function ($query) use ($propertyFlows) {
                    foreach ($propertyFlows as $value) {
                        $query->orWhereJsonContains("property_flaw", $value);
                    }
                });
            }

            if($request->purchase_method){
                // $selectedValues = $request->purchase_method;
                $purchaseMethods = array_map('intval', $request->purchase_method);

                $buyers = $buyers->where(function ($query) use ($purchaseMethods) {
                    foreach ($purchaseMethods as $value) {
                        $query->orWhereJsonContains("purchase_method", $value);
                    }
                });
                
                $additionalBuyers = $additionalBuyers->where(function ($query) use ($purchaseMethods) {
                    foreach ($purchaseMethods as $value) {
                        $query->orWhereJsonContains("purchase_method", $value);
                    }
                });
              
            }

            if($request->zoning){
                // $selectedValues = $request->zoning;
                $zonings = array_map('intval', $request->zoning);

                $buyers = $buyers->where(function ($query) use ($zonings) {
                    foreach ($zonings as $value) {
                        $query->orWhereJsonContains("zoning", $value);
                    }
                });

                $additionalBuyers = $additionalBuyers->where(function ($query) use ($zonings) {
                    foreach ($zonings as $value) {
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
            
          
            $pagination = 20;
            if($authUserLevelType == 2 && $request->activeTab == 'more_buyers'){
                $pagination = 50;
            }elseif($authUserLevelType == 3 && $request->activeTab == 'more_buyers'){
                $pagination = 50;
            }

            $buyers = $buyers
            ->withCount(['likes as likes_count'])
            ->orderByRaw('ISNULL(plan_position), plan_position ASC')
            ->orderBy('users.level_type', 'desc')
            ->orderBy('verification_count', 'desc') 
            ->orderBy('likes_count', 'desc');
            
            $buyers = $buyers->paginate($pagination);

            // Get additional buyer
            if($authUserLevelType == 3){
                 $additionalBuyers = $additionalBuyers->where('user_id', '!=', $userId);
            }else{
                 $additionalBuyers = $additionalBuyers->whereDoesntHave('buyersPurchasedByUser', function ($query) use($userId) {
                    $query->where('user_id', '=',$userId);
                })->where('user_id', '=', 1);
            }
           

            $insertLogRecords = $request->all();
            $insertLogRecords['user_id'] = $userId;
            
            $searchLogId = 0;
            if(isset($request->filterType) && $request->filterType == 'search_page'){
               
                $insertLogRecords['country'] =  233;
                $insertLogRecords['state']   =  $request->state ? json_encode($request->state,JSON_NUMERIC_CHECK) : null;
                $insertLogRecords['city']    =  $request->city ? json_encode($request->city,JSON_NUMERIC_CHECK) : null;
                $insertLogRecords['permanent_affix'] =  (!is_null($request->permanent_affix))?$request->permanent_affix : 0;
                $insertLogRecords['park']    =  $request->park;
                $insertLogRecords['rooms']    =  $request->rooms;
                $insertLogRecords['zoning']  =  ( isset($zonings) && $zonings && count($zonings) > 0) ? json_encode($zonings) : null;
                $insertLogRecords['property_flaw']  =  (isset($propertyFlows) && $propertyFlows && count($propertyFlows) > 0) ? $propertyFlows : null;
                $insertLogRecords['purchase_method']  =  (isset($purchaseMethods) && $purchaseMethods && count($purchaseMethods) > 0) ? $purchaseMethods : null;
                $insertLogRecords['picture_link'] =  $request->picture_link;
                $searchLog = SearchLog::create($insertLogRecords);
                
                $searchLogId = $searchLog->id;
                    if ($request->hasFile('attachments')) {                   
                        foreach ($request->file('attachments') as $file) {
                            $uploadedImage = uploadImage($searchLog, $file, 'search-log/attachments', "search-log", 'original', 'save', null);
                        }
                    }
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

                        $buyer->certified_closer_verified = $buyer->userDetail->buyerVerification->certified_closer_status == 'verified' ? true : false;

                        $buyer->is_application_verified = $buyer->userDetail->buyerVerification->is_application_process ? true : false;
                        //End Verification status


                    }else if($request->activeTab == 'more_buyers'){
                        // $buyer->user = $buyer->user_id;
                        
                        if($authUserLevelType == 3){
                            if($buyerDetails){
                                $buyer->name =  ucwords($name);
                                $buyer->first_name = ucwords($buyerDetails->first_name);
                                $buyer->last_name = ucwords($buyerDetails->last_name);
                                $buyer->email = $buyerDetails->email;
                                $buyer->phone = $buyerDetails->phone;
                            }
    
                            $buyer->contact_preferance_id = $buyer->contact_preferance;
                            $buyer->contact_preferance = $buyer->contact_preferance ? config('constants.contact_preferances')[$buyer->contact_preferance]: '';
                        }else{
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
                        }
                            $buyer->redFlag = $buyer->redFlagedData()->where('user_id',$userId)->exists();
                            $buyer->totalBuyerLikes = totalLikes($buyer->id);
                            $buyer->totalBuyerUnlikes = totalUnlikes($buyer->id);
                            $buyer->redFlagShow = $buyer->buyersPurchasedByUser()->where('user_id',auth()->user()->id)->exists();
                            $buyer->createdByAdmin = (($buyer->created_by == 1) || ($authUserLevelType == 3)) ? true : false;
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
     
                            $buyer->certified_closer_verified = $buyer->userDetail->buyerVerification->certified_closer_status == 'verified' ? true : false;
    
                            $buyer->is_application_verified = $buyer->userDetail->buyerVerification->is_application_process ? true : false;
                            //End Verification status
                    }
                }
            }
            
            //Return Success Response
            $responseData = [
                'status'        => true,
                'search_log_id' => $searchLogId,
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

    public function lastSearchBuyers(Request $request){
        try {
           // $radioValues = [0,1];
            $radioValues = [1];
	        $userId = auth()->user()->id;
            
            // Get Last 5 Search logs
            $searchLogData = SearchLog::where('user_id',$userId)->select('id', 'address')->orderBy('id','desc')->take(5)->get()
            ->map(function($searchLog){
                return [
                    'value' => $searchLog->id,
                    'label' => $searchLog->address,
                ];
            });

            // Get Interest status
            $buyerInterestStatus = collect(config('constants.buyer_interest_status'))->map(function ($label, $value) {
                return [
                    'value' => $value,
                    'label' => $label,
                ];
            })->values()->all();

            if($request->has('search_log_id') && !empty($request->query('search_log_id'))){
                $lastSearchLog = SearchLog::where('id',$request->query('search_log_id'))->first();
            } else {
                $lastSearchLog = SearchLog::where('user_id',$userId)->orderBy('id','desc')->first();
            }

            if($request->has('status') && !empty($request->query('status'))){
                $dealStatus = $request->query('status');
                $dealReactedBuyerIds = $lastSearchLog->buyerDeals()->whereStatus($dealStatus)->pluck('buyer_user_id')->toArray();
            }
            
            if($lastSearchLog){

            // $buyers = Buyer::query()->with(['userDetail'])->select('id','user_id','created_by','contact_preferance')->where('status', 1)->whereRelation('buyersPurchasedByUser', 'user_id', '=', $userId);

            $buyers = Buyer::select(['buyers.id', 'buyers.user_id','buyers.buyer_user_id', 'buyers.created_by', 'buyers.contact_preferance', 'buyer_plans.position as plan_position', 'buyers.is_profile_verified', 'buyers.plan_id','buyers.status'])->leftJoin('buyer_plans', 'buyer_plans.id', '=', 'buyers.plan_id')->where('buyers.status', 1)->whereRelation('buyersPurchasedByUser', 'user_id', '=', $userId);

            if(isset($dealReactedBuyerIds) && !empty($dealReactedBuyerIds)){
                $buyers = $buyers->whereIn('buyer_user_id', $dealReactedBuyerIds);
            }
            
            if($lastSearchLog->property_type){
                $selectedValues = [$lastSearchLog->property_type];

                $buyers = $buyers->where(function ($query) use ($selectedValues) {
	
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("property_type", (int)$value);
                    }
                });

            }

            /*if($lastSearchLog->address){
                $buyers = $buyers->where('address', 'like', '%'.$lastSearchLog->address.'%');
            }*/

            // if($lastSearchLog->country){
            //     $buyers = $buyers->where('country', $lastSearchLog->country);
            // }

            if($lastSearchLog->state){
                $selectedValues = json_decode($lastSearchLog->state,true);
                
                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    if($selectedValues){
                         foreach ($selectedValues as $value) {
                            $query->orWhereJsonContains("state", $value);
                        }
                    }
                   
                });
            }

            if($lastSearchLog->city){
                $selectedValues = json_decode($lastSearchLog->city,true);
                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    if($selectedValues){
                        foreach ($selectedValues as $value) {
                            $query->orWhereJsonContains("city", $value);
                        }
                    }
                });
            } 
            /*if($lastSearchLog->state){
                $buyers = $buyers->where('state', $lastSearchLog->state);
            }
            if($lastSearchLog->city){
                $buyers = $buyers->where('city', $lastSearchLog->city);
            }

            if($lastSearchLog->zip_code){
                $buyers = $buyers->where('zip_code', $lastSearchLog->zip_code);
            }*/

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

   

            if($lastSearchLog->parking){
                $selectedValues = [$lastSearchLog->parking];

                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("parking", (int)$value);
                    }
                });
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
                //$selectedValues = $lastSearchLog->zoning;
		$selectedValues = json_decode($lastSearchLog->zoning,true);
		if($selectedValues){
                $buyers = $buyers->where(function ($query) use ($selectedValues) {
                    foreach ($selectedValues as $value) {
                        $query->orWhereJsonContains("zoning", $value);
                    }
                });
		}
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

	    $addressValue = $lastSearchLog->address ? ucwords($lastSearchLog->address) : '';

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

                $buyer->certified_closer_verified = $buyer->userDetail->buyerVerification->certified_closer_status == 'verified' ? true : false;

                $buyer->is_application_verified = $buyer->userDetail->buyerVerification->is_application_process ? true : false;
                //End Verification status

            }
            
            //Return Success Response
            $responseData = [
                'status' => true,
		        'address_value'=>$addressValue,
                'deal_status' => $buyerInterestStatus,
                'last_searches' => $searchLogData,
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
		'error_details' => $e->getMessage().'->'.$e->getLine()
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

    /**
     * Send Deal to selected buyer's who is selected from searched buyer list Login Seller
     */
    public function sendDealToBuyers(Request $request){
        $request->validate([
            'search_log_id'     => ['required', 'exists:search_logs,id'],
            'buyer_user_ids'    => ['required', 'array'],
            'buyer_user_ids.*'  => ['integer', 'exists:users,id'],
            'message'           => ['nullable', 'string']
        ],[],[
            'buyer_user_ids' => 'buyer',
            'buyer_user_ids.*' => 'buyer',
        ]);

        try {
            DB::beginTransaction();
            foreach($request->buyer_user_ids as $buyerId){
                $buyerDeal = BuyerDeal::create([
                    'buyer_user_id' => $buyerId,
                    'search_log_id' => $request->search_log_id,
                    'message' => $request->message ?? null,
                ]);

                // Send Notification to Selected buyers
                $buyerUser = User::find($buyerId);
                $notificationData = [
                    'title'     => trans('notification_messages.buyer_deal.send_deal_title'),
                    'message'   => trans('notification_messages.buyer_deal.send_deal_message'),
                    'module'    => "buyer_deal",
                    'type'      => "send_deal",
                    'module_id' => $buyerDeal->id,
                    'notification_type' => 'deal_notification'
                ];
                $buyerUser->notify(new SendNotification($notificationData));

                if(isset($buyerUser->notificationSetting) && $buyerUser->notificationSetting->email_notification){
                    //Send Mail
                    $subject  = $notificationData['title'];
                    $message  = $notificationData['message'];
                    Mail::to($buyerUser->email)->queue(new DealMail($subject, $buyerUser->name, $message));
                }
            }

            DB::commit();
            //Return Success Response
            $responseData = [
                'status'    => true,
                'message'   => trans('messages.buyer_deal.success_send_deal',['total_buyer'=>count($request->buyer_user_ids)]),
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
		        'error_details' => $e->getMessage().'->'.$e->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }

    /**
     * Get Deal list for login buyer 
     */
    public function buyerDealsList(){        
        try {
            $user = auth()->user();
            $propertyTypes = config('constants.property_types');

            $dealLists = BuyerDeal::with(['searchLog', 'createdBy'])->where("buyer_user_id", $user->id)
                ->latest()
                ->paginate(20);

                $dealLists->getCollection()->transform(function ($buyerDeal) use ($propertyTypes) {
                    $searchLog = $buyerDeal->searchLog ?? null;
                    $propertType = $searchLog && $searchLog->property_type && $propertyTypes[$searchLog->property_type] ? $propertyTypes[$searchLog->property_type] : '';
                    $address = $searchLog && $searchLog->address ? $searchLog->address : '';

                    $is_proof_of_fund_verified = $buyerDeal->buyerUser->buyerVerification()->where('is_proof_of_funds', 1)->where('proof_of_funds_status','verified')->exists();
                    return [
                        'id'                => $buyerDeal->id,
                        'search_log_id'     => $searchLog->id ?? '',
                        'title'             => $address,
                        'address'           => $address,
                        'property_type'     => $propertType,
                        'property_images'   => $searchLog && $searchLog->uploads ? $searchLog->search_log_image_urls : '',
                        'picture_link'      => $searchLog && $searchLog->picture_link ? $searchLog->picture_link : '',
                        'status'            => $buyerDeal->status,
                        'is_proof_of_fund_verified'  => $is_proof_of_fund_verified,
                    ];
                });

            //Return Success Response
            $responseData = [
                'status'    => true,
                'deals'     => $dealLists,
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
		        'error_details' => $e->getMessage().'->'.$e->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }

    /**
     * Get Single Deal Detail for Login Buyer
     */
    public function buyerDealDetail($id){
        try {
            $buyerDeal = BuyerDeal::with(['searchLog', 'createdBy'])->where("id", $id)->first();
            if(!$buyerDeal){
                $responseData = [
                    'status' => false,
                    'error'  => trans('messages.buyer_deal.not_found'),
                ];
                return response()->json($responseData, 400);
            }

            //Return Success Response
            $responseData = [
                'status'    => true,
                'data'     => $buyerDeal
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
		        'error_details' => $e->getMessage().'->'.$e->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }

    /**
     * Update deal status by buyer 
     */
    public function updateBuyerDealStatus(Request $request){
        $request->validate([
            'buyer_deal_id'     => ['required', 'exists:buyer_deals,id'],
            'status'            => ['required', 'in:'.implode(',', array_keys(config('constants.buyer_deal_status')))],
            'buyer_feedback'    => ['required_if:status,not_interested', 'string'],
            'pdf_file'          => ['required_if:status,want_to_buy','mimes:pdf','max:'.config('constants.interested_pdf_size')]
        ],[
            'pdf_file.mimes'    => 'The file must be a PDF document',
        ],[
            "buyer_feedback" => "feedback"
        ]);

        DB::beginTransaction();
        try {
            $dealStatus = config('constants.buyer_deal_status');
            $buyerDeal = BuyerDeal::where('buyer_user_id', auth()->id())->whereId($request->buyer_deal_id)->first();
            if(!$buyerDeal){
                $responseData = [
                    'status' => false,
                    'error'  => trans('messages.buyer_deal.not_found'),
                ];
                return response()->json($responseData, 400);
            }
            if(!is_null($buyerDeal->status)){
                $responseData = [
                    'status' => false,
                    'error'  => trans('messages.buyer_deal.already_updated', ['status' => $dealStatus[$buyerDeal->status]]),
                ];
                return response()->json($responseData, 400);
            }

            $buyerDealId = $buyerDeal->id;
            $createdByUser = $buyerDeal->createdBy;

            $buyerDealUpdateData = [
                "status" => $request->status
            ];

            if($request->status == 'not_interested' && $request->has('buyer_feedback') && !empty($request->buyer_feedback)){
                $buyerDealUpdateData['buyer_feedback'] = $request->buyer_feedback;
            }

            $isUpdated = $buyerDeal->update($buyerDealUpdateData);

            $uploadedPdfFile = $request->file('pdf_file');
            if($request->status == 'want_to_buy' && $uploadedPdfFile){
                $uploadId = null;
                $actionType = 'save';
                if($uploadedDealPdf = $buyerDeal->wantToBuyDealPdf){
                    $uploadId = $uploadedDealPdf->id;
                    $actionType = 'update';
                }

                uploadImage($buyerDeal, $uploadedPdfFile, 'buyer-deals/want_to_buy', "want-to-buy-deal-pdf", 'original', $actionType, $uploadId);
            }

            // Send Notification to seller after deal status update
            if($isUpdated && $createdByUser){
                $notificationData = [
                    'title'     => trans('notification_messages.buyer_deal.update_deal_status_title'),
                    'message'   => trans('notification_messages.buyer_deal.update_deal_status_message', ['status' => $dealStatus[$request->status]]),
                    'module'    => "buyer_deal",
                    'type'      => "update_status",
                    'module_id' => $buyerDealId,
                    'notification_type' => 'deal_notification'
                ];
                Notification::send($createdByUser, new SendNotification($notificationData));

                if(isset($createdByUser->notificationSetting) && $createdByUser->notificationSetting->email_notification){
                    //Send Mail
                    $subject  = $notificationData['title'];
                    $message  = $notificationData['message'];
                    Mail::to($createdByUser->email)->queue(new DealMail($subject, $createdByUser->name, $message));
                }
                
            }
            
            DB::commit();
            //Return Success Response
            $responseData = [
                'status'    => true,
                'message'   => trans('messages.update_status', ['module_name' => "Deal"]),
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
		        'error_details' => $e->getMessage().'->'.$e->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }

    /**
     * Get List of Deals for Seller with Selected status count
     */
    public function sellerDealResultList(){
        try {
            $authUser = auth()->user();

            $seachLogDeals = SearchLog::with(['buyerDeals'])->whereHas('buyerDeals')
            ->where('user_id', $authUser->id)
            ->withCount([
                'buyerDeals as want_to_buy_count' => function ($query) {
                    $query->where('status', 'want_to_buy');
                },
                'buyerDeals as interested_count' => function ($query) {
                    $query->where('status', 'interested');
                },
                'buyerDeals as not_interested_count' => function ($query) {
                    $query->where('status', 'not_interested');
                }
            ])
            ->latest()->paginate(20);

            $seachLogDeals->getCollection()->transform(function ($seachLogDeal) {            
                $address = $seachLogDeal && $seachLogDeal->address ? $seachLogDeal->address : '';
                
                $record = [
                    'id'                => $seachLogDeal->id,
                    'title'             => $address,
                    'address'           => $address,
                    'property_images'   => $seachLogDeal->uploads ? $seachLogDeal->search_log_image_urls : '',
                    
                    "total_buyer"       => $seachLogDeal->buyerDeals()->count(),
                    'want_to_buy_count' => $seachLogDeal->want_to_buy_count,
                    'interested_count'  => $seachLogDeal->interested_count,
                    'not_interested_count' => $seachLogDeal->not_interested_count,
                ];
                
                return $record;
                
                
            });

            //Return Success Response
            $responseData = [
                'status'    => true,
                'deals'     => $seachLogDeals
            ];
            return response()->json($responseData, 200);
        } catch (\Throwable $th) {
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
		        'error_details' => $th->getMessage().'->'.$th->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }

    /**
     * Get Single Deal Detail for Login Buyer
     */
    public function sellerDealDetail($id, $status=''){
        try {
            $dealStatus = array_keys(config('constants.buyer_deal_status'));

            $searchLog = SearchLog::with(['buyerDeals'])->where("id", $id)            
            ->withCount([
                'buyerDeals as want_to_buy_count' => function ($query) {
                    $query->where('status', 'want_to_buy');
                },
                'buyerDeals as interested_count' => function ($query) {
                    $query->where('status', 'interested');
                },
                'buyerDeals as not_interested_count' => function ($query) {
                    $query->where('status', 'not_interested');
                }
            ])
            ->first();
            
            $searchlogBuyerDeals = $searchLog->buyerDeals()->with(['buyerUser']);

            if($status){
                $searchlogBuyerDeals = $searchlogBuyerDeals->where('status', $status);
            }

            $searchlogBuyerDeals = $searchlogBuyerDeals->latest()->paginate(20);

            $searchlogBuyerDeals->getCollection()->transform(function ($searchlogBuyerDeal) {
                $buyerUser = $searchlogBuyerDeal->buyerUser;
                $record = [
                    'deal_id'           => $searchlogBuyerDeal->id,
                    'buyer_user_id'     => $searchlogBuyerDeal->buyer_user_id,                    

                    'buyer_name'        => $buyerUser->name,
                    'buyer_email'       => $buyerUser->email,
                    'buyer_phone'       => $buyerUser->phone,
                    'profile_image'     => $buyerUser->profile_image_url,
                    'status'            => $searchlogBuyerDeal->status ? config('constants.buyer_deal_status')[$searchlogBuyerDeal->status] : '',
                    
                ];
                
                if($searchlogBuyerDeal->status == 'want_to_buy'){
                    $record['want_to_buy_deal_pdf_url'] =  $searchlogBuyerDeal->want_to_buy_deal_pdf_url;
                }
                
                return $record;
            });
            
            $address = $searchLog && $searchLog->address ? $searchLog->address : '';
            $propertyImages = $searchLog->uploads ? $searchLog->search_log_image_urls : '';
            $dealData = [
                "id"                    => $id,
                'title'                 => $address,
                'address'               => $address,
                'property_images'       => $propertyImages,

                "total_buyer"           => $searchLog->buyerDeals()->count(),
                'want_to_buy_count'     => $searchLog->want_to_buy_count,
                'interested_count'      => $searchLog->interested_count,
                'not_interested_count'  => $searchLog->not_interested_count,

                // buyer listing with status filter
                "buyers"                => $searchlogBuyerDeals
            ];

            //Return Success Response
            $responseData = [
                'status'    => true,
                'data'     => $dealData
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
		        'error_details' => $e->getMessage().'->'.$e->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }
}
