<?php

namespace App\Http\Controllers\Api\User;

use Carbon\Carbon;
use App\Models\Buyer;
use App\Models\User;
use App\Models\UserBuyerLikes;
use App\Models\PurchasedBuyer;
use App\Models\Token;
use Illuminate\Support\Str;
use App\Models\SearchLog;
use App\Imports\BuyersImport;
use Illuminate\Support\Arr;
use App\Rules\CheckMaxValue;
use App\Rules\CheckMinValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\SearchBuyersRequest;
use App\Http\Requests\StoreSingleBuyerDetailsRequest;


class BuyerController extends Controller
{
    public function getCountries(){
        //Return Success Response
        $countries = DB::table('countries')->orderBy('name','ASC')->pluck('name','id');

        $options = $countries->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => ucfirst(strtolower($label)),
            ];
        })->values()->all();
      
        return response()->json(['options'=>$options], 200);
   }

   public function getStates(Request $request){
       $country_id = $request->country_id;
        //Return Success Response
        $states = DB::table('states')->where('country_id',$country_id)->orderBy('name','ASC')->pluck('name','id');

        $options = $states->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => ucfirst(strtolower($label)),
            ];
        })->values()->all();
    
        return response()->json(['options'=>$options], 200);
    }

    public function getCities(Request $request){
        $country_id = $request->country_id;
        $state_id   = $request->state_id;

        //Return Success Response
        $cities = DB::table('cities')->where('country_id',$country_id)->where('state_id',$state_id)->orderBy('name','ASC')->pluck('name','id');

        $options = $cities->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => ucfirst(strtolower($label)),
            ];
        })->values()->all();
    
        return response()->json(['options'=>$options], 200);
    }

    public function getPropertyTypes(){
        //Return Success Response
        $options = collect(config('constants.property_types'))->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => ucfirst(strtolower($label)),
            ];
        })->values()->all();
      
        return response()->json(['options'=>$options], 200);
   }

    public function getBuildingClassNames(){
        //Return Success Response
        $options = collect(config('constants.building_class_values'))->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => ucfirst(strtolower($label)),
            ];
        })->values()->all();
      
        return response()->json(['options'=>$options], 200);
    }

    public function getPurchaseMethods(){
        //Return Success Response
        $options = collect(config('constants.purchase_methods'))->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => ucfirst(strtolower($label)),
            ];
        })->values()->all();
      
        return response()->json(['options'=>$options], 200);
    }

    public function getParkings(){
        //Return Success Response
        $options = collect(config('constants.parking_values'))->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => ucfirst(strtolower($label)),
            ];
        })->values()->all();
      
        return response()->json(['options'=>$options], 200);
    }

    public function getLocationFlaws(){
        //Return Success Response
        $options = collect(config('constants.property_flaws'))->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => ucfirst(strtolower($label)),
            ];
        })->values()->all();
      
        return response()->json(['options'=>$options], 200);
    }
   
    public function singleBuyerFormElementValues(){
        $elementValues = [];
        try{
            $elementValues['property_types'] = collect(config('constants.property_types'))->map(function ($label, $value) {
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

            $countries = DB::table('countries')->orderBy('name','ASC')->pluck('name','id');
            $elementValues['countries'] = $countries->map(function ($label, $value) {
                return [
                    'value' => $value,
                    'label' => ucwords(strtolower($label)),
                ];
            })->values()->all();

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

            $elementValues['market_preferances'] = collect(config('constants.market_preferances'))->map(function ($label, $value) {
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

            //Return Error Response
            $responseData = [
                'status'        => true,
                'result'        => $elementValues,
            ];
            return response()->json($responseData, 200);

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

    public function uploadSingleBuyerDetails(StoreSingleBuyerDetailsRequest $request,$token = null){
        DB::beginTransaction();
        try {
            $validatedData = $request->all();

            // Start token functionality
            if($token){
                $checkToken = Token::where('token_value',$token)->where('is_used',1)->first();
                if($checkToken){
                     //Return Error Response
                     $responseData = [
                        'status'        => false,
                        'error_type'    => 'token_error',
                        'error'         => 'Invalid Token!',
                    ];
                    return response()->json($responseData, 400);
                }else{
                    $validatedData['user_id'] = Token::where('token_value',$token)->value('user_id');
                }
               
            }else{
                $validatedData['user_id'] = auth()->user()->id;
            }
            // End token functionality

            $validatedData['country'] =  DB::table('countries')->where('id',$request->country)->value('name');

            if($request->state){
                $validatedData['state']   =  DB::table('states')->where('id',$request->state)->value('name');
            }

            if($request->city){
                $validatedData['city']    =  DB::table('cities')->where('id',$request->city)->value('name');
            }

          
            if($request->parking){
                $validatedData['parking'] = (int)$request->parking;
            }
          
            if($request->buyer_type){
                $validatedData['buyer_type'] = (int)$request->buyer_type;
            }

            if($request->zoning){
                $validatedData['zoning'] = json_encode($request->zoning);
            }
           
            $createdBuyer = Buyer::create($validatedData);
            
            if($token){
                //Purchased buyer
                $syncData['buyer_id'] = $createdBuyer->id;
                $syncData['created_at'] = Carbon::now();
        
                User::where('id',$validatedData['user_id'])->first()->purchasedBuyers()->create($syncData);

            }else{
                if(auth()->user()->is_seller){
                    //Purchased buyer
                    $syncData['buyer_id'] = $createdBuyer->id;
                    $syncData['created_at'] = Carbon::now();
              
                    auth()->user()->purchasedBuyers()->create($syncData);
                }
            }
            

            if($token){
              Token::where('token_value',$token)->update(['is_used'=>1]);
            }

            DB::commit();
            
            //Success Response Send
            $responseData = [
                'status'            => true,
                'message'           => 'Buyer details uploaded successfully!',
            ];
            return response()->json($responseData, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine(),'->'.json_encode(array_keys(config('constants.zonings'))));
            
            //Return Error Response
            $responseData = [
                'status'        => false,
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

            $buyers = Buyer::query()->select('id','user_id','first_name','last_name','email','phone','contact_preferance','created_by');
            $additionalBuyers = Buyer::query();

            if($request->activeTab){
                if($request->activeTab == 'my_buyers'){
                    $buyers = $buyers->whereRelation('buyersPurchasedByUser', 'user_id', '=', $userId);
                }elseif($request->activeTab == 'more_buyers'){
                    $buyers = $buyers->whereDoesntHave('buyersPurchasedByUser', function ($query) use($userId) {
                        $query->where('user_id', '=',$userId);
                    })->where('user_id', '=', 1);
                }
            }

            $buyers = $buyers->where('status', 1);
        
            if($request->property_type){
                $propertyType = $request->property_type;
                $buyers = $buyers->whereJsonContains('property_type', intval($propertyType));
                $additionalBuyers = $additionalBuyers->whereJsonContains('property_type', intval($propertyType));
            }

            if($request->address){
                $buyers = $buyers->where('address', 'like', '%'.$request->address.'%');
                $additionalBuyers = $additionalBuyers->where('address', 'like', '%'.$request->address.'%');
            }

            if($request->country){
                $country =  DB::table('countries')->where('id',$request->country)->value('name');
                $buyers = $buyers->where('country', $country);
                $additionalBuyers = $additionalBuyers->where('country', $country);
            }

            if($request->state){
                $state   =  DB::table('states')->where('id',$request->state)->value('name');
                $buyers = $buyers->where('state', $state);
                $additionalBuyers = $additionalBuyers->where('state', $state);
            }

            if($request->city){
                $city    =  DB::table('cities')->where('id',$request->city)->value('name');
                $buyers = $buyers->where('city', $city);
                $additionalBuyers = $additionalBuyers->where('city', $city);
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
                $buyers = $buyers->whereJsonContains('parking', intval($request->parking));
                $additionalBuyers = $additionalBuyers->whereJsonContains('parking', intval($request->parking));
            }

            if($request->property_flaw){
                $buyers = $buyers->whereJsonContains('property_flaw', $request->property_flaw);
                $additionalBuyers = $additionalBuyers->whereJsonContains('property_flaw', $request->property_flaw);
            }

            if($request->purchase_method){
                $buyers = $buyers->whereJsonContains('purchase_method', $request->purchase_method);
                $additionalBuyers = $additionalBuyers->whereJsonContains('purchase_method', $request->purchase_method);
            }

            if($request->zoning){
                $buyers = $buyers->whereJsonContains('zoning', $request->zoning);
                $additionalBuyers = $additionalBuyers->whereJsonContains('zoning', $request->zoning);
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
                $buyers = $buyers->where('market_preferance', $request->market_preferance);
                $additionalBuyers = $additionalBuyers->where('market_preferance', $request->market_preferance);
            }

            if($request->contact_preferance){
                $buyers = $buyers->where('contact_preferance', $request->contact_preferance);
                $additionalBuyers = $additionalBuyers->where('contact_preferance', $request->contact_preferance);
            }

            /* if($request->building_class){
                $buyers = $buyers->whereJsonContains('building_class', $request->building_class);
            } */

            if($request->of_stories && is_numeric($request->of_stories)){
                $of_stories_value = $request->of_stories;
                $buyers = $buyers->where(function ($query) use ($of_stories_value) {
                    $query->where('of_stories_min', '<=', $of_stories_value)
                          ->where('of_stories_max', '>=', $of_stories_value);
                });
                $additionalBuyers = $additionalBuyers->where(function ($query) use ($of_stories_value) {
                    $query->where('of_stories_min', '<=', $of_stories_value)
                          ->where('of_stories_max', '>=', $of_stories_value);
                });
            } 


            if(!is_null($request->solar) && in_array($request->solar, $radioValues)){
                $buyers = $buyers->where('solar', $request->solar);
                $additionalBuyers = $additionalBuyers->where('solar', $request->solar);
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
                $buyers = $buyers->whereJsonContains('building_class', intval($request->building_class));
                $additionalBuyers = $additionalBuyers->whereJsonContains('building_class', intval($request->building_class));
            }

            if(!is_null($request->value_add) && in_array($request->value_add, $radioValues)){
                $buyers = $buyers->where('value_add', $request->value_add);
                $additionalBuyers = $additionalBuyers->where('value_add', $request->value_add);
            }

            if($request->buyer_type){
                $buyers = $buyers->whereJsonContains('buyer_type', intval($request->buyer_type));
                $additionalBuyers = $additionalBuyers->whereJsonContains('buyer_type', intval($request->buyer_type));
            }
           
            $totalRecord = $buyers->count();
            
            $authUserLevelType = auth()->user()->level_type;

            $pagination = 10;
            if($authUserLevelType == 2 && $request->activeTab == 'more_buyers'){
                $pagination = 20;
            }elseif($authUserLevelType == 3 && $request->activeTab == 'more_buyers'){
                $pagination = 50;
            }

            $buyers = $buyers->paginate($pagination);

            // Get additional buyer
            $additionalBuyers = $additionalBuyers->whereDoesntHave('buyersPurchasedByUser', function ($query) use($userId) {
                $query->where('user_id', '=',$userId);
            })->where('user_id', '=', 1);

            $insertLogRecords = $request->all();
            $insertLogRecords['user_id'] = $userId;

            if(isset($request->filterType) && $request->filterType == 'search_page'){

                $insertLogRecords['country'] =  DB::table('countries')->where('id',$request->country)->value('name');
                $insertLogRecords['state']   =  DB::table('states')->where('id',$request->state)->value('name');
                $insertLogRecords['city']    =  DB::table('cities')->where('id',$request->city)->value('name');
                $insertLogRecords['zoning']  =  ($request->zoning && count($request->zoning) > 0) ? json_encode($request->zoning) : null;
                SearchLog::create($insertLogRecords);
            }

            foreach ($buyers as $key=>$buyer){
                $name = $buyer->first_name.' '.$buyer->last_name;
                if($request->activeTab){
                    if($request->activeTab == 'my_buyers'){
                        $buyer->name =  $name;
                        $buyer->contact_preferance = $buyer->contact_preferance ? config('constants.contact_preferances')[$buyer->contact_preferance]: '';
                        $buyer->redFlag = $buyer->redFlagedData()->where('user_id',$userId)->exists();
                        $buyer->totalBuyerLikes = totalLikes($buyer->id);
                        $buyer->totalBuyerUnlikes = totalUnlikes($buyer->id);
                        $buyer->redFlagShow = $buyer->buyersPurchasedByUser()->exists();
                        $buyer->createdByAdmin = ($buyer->created_by == 1) ? true : false;

                    }else if($request->activeTab == 'more_buyers'){
                        // $buyer->user = $buyer->user_id;
                        $buyer->name  =  substr($name, 0, 3).str_repeat("X", strlen($name)-3);
                        $buyer->email =  substr($buyer->email, 0, 3).str_repeat("X", strlen($buyer->email)-3);
                        $buyer->phone =  substr($buyer->phone, 0, 3).str_repeat("X", strlen($buyer->phone)-3);
                        $buyer->contact_preferance = $buyer->contact_preferance ? config('constants.contact_preferances')[$buyer->contact_preferance]: '';
                        $buyer->redFlag = $buyer->redFlagedData()->where('user_id',$userId)->exists();
                        $buyer->totalBuyerLikes = totalLikes($buyer->id);
                        $buyer->totalBuyerUnlikes = totalUnlikes($buyer->id);
                        $buyer->redFlagShow = $buyer->buyersPurchasedByUser()->exists();
                        $buyer->createdByAdmin = ($buyer->created_by == 1) ? true : false;
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
            ];
            return response()->json($responseData, 400);
        }

    }

    public function fetchBuyers(Request $request){
        $validator = Validator::make($request->all(), [
            'property_type'  => 'int',
        ]);

        if($validator->fails()){
             //Error Response Send
             $responseData = [
                'status'        => false,
                'validation_errors' => $validator->errors(),
            ];
            return response()->json($responseData, 400);
        }

        DB::beginTransaction();
        try {
            $perPage = 10;
            $userId = auth()->user()->id;
            $totalBuyers = Buyer::whereRelation('buyersPurchasedByUser', 'user_id', '=', $userId)->count();
            $buyers = Buyer::select('id','first_name','last_name','email','phone')->whereRelation('buyersPurchasedByUser', 'user_id', '=', $userId)->paginate($perPage);
        
            foreach ($buyers as $buyer) {
                $buyer->contact_preferance = $buyer->contact_preferance ? config('constants.contact_preferances')[$buyer->contact_preferance]: '';
                $buyer->redFlag = $buyer->redFlagedData()->where('user_id',$userId)->exists();
                $buyer->totalBuyerLikes = totalLikes($buyer->id);
                $buyer->totalBuyerUnlikes = totalUnlikes($buyer->id);
                $buyer->redFlagShow = $buyer->buyersPurchasedByUser()->exists();
                $buyer->createdByAdmin = ($buyer->created_by == 1) ? true : false;
            }

            DB::commit();

            //Return Success Response
            $responseData = [
                'status'        => true,
                'buyers'        => $buyers,
                'totalBuyers'   => $totalBuyers
            ];

            return response()->json($responseData, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 400);
        }

    }

    public function import(Request $request)
    {
        $request->validate([
            'csvFile' => 'required|mimes:csv,xlsx,xls',
        ]);

        // Create an array of rules for each column in the CSV sheet.
        $import = new BuyersImport;
        Excel::import($import, $request->file('csvFile'));

        try {
            $totalCount         = $import->totalRowCount();
            $insertedRowCount   = $import->insertedCount();
            $skippedCount       = $totalCount - $insertedRowCount;

            // dd($totalCount, $insertedRowCount, $skippedCount);
            
            if($insertedRowCount == 0){
                //Return Error Response
                $responseData = [
                    'status'        => false,
                    'message'       => trans('No rows inserted during the import process.'),
                ];
                return response()->json($responseData, 400);

            } else if($skippedCount > 0 && $insertedRowCount > 0){
                $message = "{$insertedRowCount} out of {$totalCount} rows inserted successfully.";
            
                //Return Error Response
                $responseData = [
                    'status'        => true,
                    'message'       => $message,
                ];
                return response()->json($responseData, 200);

            } else if($skippedCount == 0) {
                //Return Success Response
                $responseData = [
                    'status'        => true,
                    'message'       => 'Buyers imported successfully!',
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

    public function copySingleBuyerFormLink(){
        try {
            $authUserId = auth()->user()->id;

            $token = Str::random(32);
            $currentDateTime = Carbon::now();

            $tokenRecords = [
                'user_id'            => $authUserId,
                'token_value'        => $token,
                'token_expired_time' => $currentDateTime->addMinutes(config('constants.token_expired_time')),
                'is_used'            => 0,
            ];

            // $checkToken = Token::where('user_id',$authUserId)->where(function($query){
            //     $query->where('token_expired_time', '<=', Carbon::now())
            //     ->orWhere('is_used',1);
            // })->first();

            $checkToken = Token::where('user_id',$authUserId)->where('is_used',1)->first();

            $isTokenGenerated = false;
            if($checkToken){
                Token::where('token_value',$checkToken->token_value)->update($tokenRecords);
                $isTokenGenerated = true;
            }else{
                Token::create($tokenRecords);
                $isTokenGenerated = true;
            }

            if($isTokenGenerated){
                //Return Success Response
                $responseData = [
                    'status'        => true,
                    'data'          => ['copy_token'=>$token],
                ];

                return response()->json($responseData, 200);
            }else{
                //Return Error Response
                $responseData = [
                    'status'        => false,
                    'error'         => trans('messages.error_message'),
                ];
                return response()->json($responseData, 400);
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

    public function isValidateToken($token){
        $tokenExpired = $this->checkTokenValidate($token);

        if($tokenExpired){
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error_type'    => 'token_expired',
                'error'         => 'Token has been expired!',
            ];
            return response()->json($responseData, 400);
        }else{
            //Success Response Send
            $responseData = [
                'status'            => true,
                'message'           => 'Token is validate!',
            ];
            return response()->json($responseData, 200);
        }

    }

    private function checkTokenValidate($token){
        $currentDateTime = Carbon::now();
        $tokenExpired = true;
        $checkToken = Token::where('token_value',$token)->where('is_used',0)->first();
        if($checkToken){
            if($checkToken->token_expired_time > $currentDateTime) {
                $tokenExpired = false;
            }
        }

        return $tokenExpired;
    }

    public function redFlagBuyer(Request $request){
        DB::beginTransaction();
        try {
          
            $authUserId = auth()->user()->id;
            $redFlagRecord[$authUserId]['reason'] = $request->reason;
            $buyer = Buyer::find($request->buyer_id);
           
            if(!$buyer->redFlagedData()->exists()){
                // $buyer->redFlagedData()->sync($redFlagRecord);
                $buyer->redFlagedData()->attach($redFlagRecord);

                DB::commit();
                //Return Success Response
                $responseData = [
                    'status'        => true,
                    'message'       => 'Flag added successfully!',
                ];
                
                return response()->json($responseData, 200);
            }else{
                 //Return Error Response
                $responseData = [
                    'status'        => false,
                    'error'         => 'Flag already added!',
                ];
                return response()->json($responseData, 400);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 400);
        }
    }

    public function unhideBuyer(Request $request){
        DB::beginTransaction();
        try {
            $fetchBuyer = Buyer::find($request->buyer_id);
            if(auth()->user()->is_seller){
                //Purchased buyer
                $syncData['buyer_id'] = $fetchBuyer->id;
                $syncData['created_at'] = Carbon::now();

                auth()->user()->purchasedBuyers()->create($syncData);
            }
        
            $fetchBuyer->redFlag = $fetchBuyer->redFlagedData()->where('user_id',auth()->user()->id)->exists();
            $fetchBuyer->totalBuyerLikes = totalLikes($fetchBuyer->id);
            $fetchBuyer->totalBuyerUnlikes = totalUnlikes($fetchBuyer->id);
            $fetchBuyer->redFlagShow = $fetchBuyer->buyersPurchasedByUser()->exists();
           
            DB::commit();

            //Success Response Send
            $responseData = [
                'status'   => true,
                'data'     => ['buyer' => $fetchBuyer]
            ];
            return response()->json($responseData, 200);

        }catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 400);
        }
    }

    public function storeBuyerLikeOrUnlike(Request $request){
        $request->validate([
            'buyer_id' => ['required','numeric'],
            'like'     => ['required','in:0,1'],
            'unlike'   => ['required','in:0,1'],
        ]);

        DB::beginTransaction();
        try {
            $fetchBuyer = Buyer::find($request->buyer_id);
            if($fetchBuyer){
              
                $userId = auth()->user()->id;
                $flag = false;

                $buyerData['liked']      = (int)$request->like;
                $buyerData['disliked']   = (int)$request->unlike;

                $entryExists = UserBuyerLikes::where('user_id',$userId)->where('buyer_id',$fetchBuyer->id)->exists();
                if($entryExists){
                    $buyerData['updated_at'] = Carbon::now();
                    UserBuyerLikes::where('user_id',$userId)->where('buyer_id',$fetchBuyer->id)->update($buyerData);
                    $flag = true;
                }else{
                    $buyerData['user_id']    = $userId;
                    $buyerData['buyer_id']   = $fetchBuyer->id;
                    $buyerData['created_at'] = Carbon::now();
                    UserBuyerLikes::create($buyerData);
                    $flag = true;
                }

                DB::commit();
    
                if($flag){
                    $responseData['totalBuyerLikes'] = totalLikes($fetchBuyer->id);
                    $responseData['totalBuyerUnlikes'] = totalUnlikes($fetchBuyer->id);

                    //Success Response Send
                    $responseData = [
                        'status'   => true,
                        'data'     => $responseData,
                        'message'  => 'Updated Successfully!'
                    ];
                    return response()->json($responseData, 200);

                }else{
                    //Return Error Response
                    $responseData = [
                        'status'        => false,
                        'error'         => trans('messages.error_message'),
                    ];
                    return response()->json($responseData, 400);
                }
               
            }
        }catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
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

            $buyers = Buyer::query()->select('id','user_id','first_name','last_name','email','phone','created_by','contact_preferance')->where('status', 1)->whereRelation('buyersPurchasedByUser', 'user_id', '=', $userId);

            
            if($lastSearchLog->property_type){
                $propertyType = $lastSearchLog->property_type;
                $buyers = $buyers->whereJsonContains('property_type', intval($propertyType));
            }

            if($lastSearchLog->address){
                $buyers = $buyers->where('address', 'like', '%'.$lastSearchLog->address.'%');
            }

            if($lastSearchLog->country){
                $buyers = $buyers->where('country', $lastSearchLog->country);
            }

            if($lastSearchLog->state){
                $buyers = $buyers->where('state', $lastSearchLog->state);
            }

            if($lastSearchLog->city){
                $buyers = $buyers->where('city', $lastSearchLog->city);
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
                $additionalBuyers = $additionalBuyers->where(function ($query) use ($priceValue) {
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

            if($lastSearchLog->arv && is_numeric($lastSearchLog->arv)){
                $arvValue = $lastSearchLog->arv;
                $buyers = $buyers->where(function ($query) use ($arvValue) {
                    $query->where('arv_min', '<=', $arvValue)
                          ->where('arv_max', '>=', $arvValue);
                });
                
            }

            if($lastSearchLog->parking){
                $buyers = $buyers->whereJsonContains('parking', intval($lastSearchLog->parking));
            }

            if($lastSearchLog->property_flaw){
                $buyers = $buyers->whereJsonContains('property_flaw', $lastSearchLog->property_flaw);
            }

            if($lastSearchLog->purchase_method){
                $buyers = $buyers->whereJsonContains('purchase_method', $lastSearchLog->purchase_method);
            }

            if($lastSearchLog->zoning){
                $buyers = $buyers->whereJsonContains('zoning', $lastSearchLog->zoning);
            }

            if($lastSearchLog->utilities){
                $buyers = $buyers->where('utilities', $lastSearchLog->utilities);
            }

            if($lastSearchLog->sewer){
                $buyers = $buyers->where('sewer', $lastSearchLog->sewer);
            }

            if($lastSearchLog->market_preferance){
                $buyers = $buyers->where('market_preferance', $lastSearchLog->market_preferance);
            }

            if($lastSearchLog->contact_preferance){
                $buyers = $buyers->where('contact_preferance',$lastSearchLog->contact_preferance);
            }

            /* if($lastSearchLog->building_class){
                $buyers = $buyers->whereJsonContains('building_class', $lastSearchLog->building_class);
            } */


            if($lastSearchLog->of_stories && is_numeric($lastSearchLog->of_stories)){
                $of_stories_value = $lastSearchLog->of_stories;
                $buyers = $buyers->where(function ($query) use ($of_stories_value) {
                    $query->where('of_stories_min', '<=', $of_stories_value)
                          ->where('of_stories_max', '>=', $of_stories_value);
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

            if($lastSearchLog->buyer_type){
                $buyers = $buyers->whereJsonContains('buyer_type', intval($lastSearchLog->buyer_type));
            }

            $buyers = $buyers->paginate(10);

            foreach ($buyers as $key=>$buyer){
                $name = $buyer->first_name.' '.$buyer->last_name;
                $buyer->name =  $name;
                $buyer->contact_preferance = $buyer->contact_preferance ? config('constants.contact_preferances')[$buyer->contact_preferance]: '';
                $buyer->redFlag = $buyer->redFlagedData()->where('user_id',$userId)->exists();
                $buyer->totalBuyerLikes = totalLikes($buyer->id);
                $buyer->totalBuyerUnlikes = totalUnlikes($buyer->id);
                $buyer->redFlagShow = $buyer->buyersPurchasedByUser()->exists();
                $buyer->createdByAdmin = ($buyer->created_by == 1) ? true : false;
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
        $searchLog = SearchLog::where('user_id',auth()->user()->id)->orderBy('id','desc')->first();

        //Success Response Send
         $responseData = [
            'status'   => true,
            'data'     => ['searchLog' => $searchLog]
        ];

        return response()->json($responseData, 200);
    }
}
