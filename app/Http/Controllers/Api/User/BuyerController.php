<?php

namespace App\Http\Controllers\Api\User;

use Carbon\Carbon;
use App\Models\Buyer;
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
                    'label' => ucfirst(strtolower($label)),
                ];
            })->values()->all();

            $elementValues['building_class_values'] = collect(config('constants.building_class_values'))->map(function ($label, $value) {
                return [
                    'value' => $value,
                    'label' => ucfirst(strtolower($label)),
                ];
            })->values()->all();

            $elementValues['purchase_methods'] = collect(config('constants.purchase_methods'))->map(function ($label, $value) {
                return [
                    'value' => $value,
                    'label' => ucfirst(strtolower($label)),
                ];
            })->values()->all();

            $elementValues['parking_values'] = collect(config('constants.parking_values'))->map(function ($label, $value) {
                return [
                    'value' => $value,
                    'label' => ucfirst(strtolower($label)),
                ];
            })->values()->all();

            $elementValues['location_flaws'] = collect(config('constants.property_flaws'))->map(function ($label, $value) {
                return [
                    'value' => $value,
                    'label' => ucfirst(strtolower($label)),
                ];
            })->values()->all();

            $elementValues['buyer_types'] = collect(config('constants.buyer_types'))->map(function ($label, $value) {
                return [
                    'value' => $value,
                    'label' => ucfirst(strtolower($label)),
                ];
            })->values()->all();

            $countries = DB::table('countries')->orderBy('name','ASC')->pluck('name','id');
            $elementValues['countries'] = $countries->map(function ($label, $value) {
                return [
                    'value' => $value,
                    'label' => ucfirst(strtolower($label)),
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
                    $validatedData['user_id'] = Token::where('token_value',$token)->value('user_id');
                }
               
            }else{
                $validatedData['user_id'] = auth()->user()->id;
            }
            // End token functionality

            $validatedData['country'] =  DB::table('countries')->where('id',$request->country)->value('name');

            $validatedData['state']   =  DB::table('states')->where('id',$request->state)->value('name');
            $validatedData['city']    =  DB::table('cities')->where('id',$request->city)->value('name');

            Buyer::create($validatedData);

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
            // dd($e->getMessage().'->'.$e->getLine());
            
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

            $buyers = Buyer::query();

            if($request->activeTab){
                if($request->activeTab == 'my_buyers'){
                    $buyers = $buyers->where('user_id',$userId);
                }elseif($request->activeTab == 'more_buyers'){
                    $buyers = $buyers->where('user_id','!=',$userId);
                }
            }

            $buyers = $buyers->where('status', 1);
        
            if($request->property_type){
                $propertyType = $request->property_type;
                $buyers->whereJsonContains('property_type', intval($propertyType));
            }

            if($request->address){
                $buyers->where('address', 'like', '%'.$request->address.'%');
            }

            if($request->country){
                $country =  DB::table('countries')->where('id',$request->country)->value('name');
                $buyers->where('country', $country);
            }

            if($request->state){
                $state   =  DB::table('states')->where('id',$request->state)->value('name');
                $buyers->where('state', $state);
            }

            if($request->city){
                $city    =  DB::table('cities')->where('id',$request->city)->value('name');
                $buyers->where('city', $city);
            }

            if($request->zip_code){
                $buyers->where('zip_code', $request->zip_code);
            }

            /* if($request->price){
                $buyers->where('price', $request->price);
            } */

            if($request->bedroom_min && is_numeric($request->bedroom_min)){
                $buyers = $buyers->where('bedroom_min', '>=', $request->bedroom_min);
            } 

            if($request->bedroom_max && is_numeric($request->bedroom_max)){
                $buyers = $buyers->where('bedroom_max', '<=', $request->bedroom_max);
            } 

            if($request->bath_min && is_numeric($request->bath_min)){
                $buyers = $buyers->where('bath_min', '>=', $request->bath_min);
            } 

            if($request->bath_max && is_numeric($request->bath_max)){
                $buyers = $buyers->where('bath_max', '<=', $request->bath_max);
            } 

            if($request->size_min && is_numeric($request->size_min)){
                $buyers = $buyers->where('size_min', '>=', $request->size_min);
            } 

            if($request->size_max && is_numeric($request->size_max)){
                $buyers = $buyers->where('size_max', '<=', $request->size_max);
            } 

            if($request->lot_size_min && is_numeric($request->lot_size_min)){
                $buyers = $buyers->where('lot_size_min', '>=', $request->lot_size_min);
            } 

            if($request->lot_size_max && is_numeric($request->lot_size_max)){
                $buyers = $buyers->where('lot_size_max', '<=', $request->lot_size_max);
            }
            
            if($request->build_year_min && is_numeric($request->build_year_min)){
                $buyers = $buyers->where('build_year_min', '>=', $request->build_year_min);
            }

            if($request->build_year_max && is_numeric($request->build_year_max)){
                $buyers = $buyers->where('build_year_max', '<=', $request->build_year_max);
            }

            if($request->arv_min && is_numeric($request->arv_min)){
                $buyers = $buyers->where('arv_min', '>=', $request->arv_min);
            }

            if($request->arv_max && is_numeric($request->arv_max)){
                $buyers = $buyers->where('arv_max', '<=', $request->arv_max);
            }

            if($request->parking){
                $buyers = $buyers->whereJsonContains('parking', intval($request->parking));
            }

            if($request->property_flaw){
                $buyers = $buyers->whereJsonContains('property_flaw', $request->property_flaw);
            }

            if($request->purchase_method){
                $buyers = $buyers->whereJsonContains('purchase_method', $request->purchase_method);
            }

            /* if($request->building_class){
                $buyers = $buyers->whereJsonContains('building_class', $request->building_class);
            } */


            if(!is_null($request->solar) && in_array($request->solar, $radioValues)){
                $buyers = $buyers->where('solar', $request->solar);
            }

            if(!is_null($request->pool) && in_array($request->pool, $radioValues)){
                $buyers = $buyers->where('pool', $request->pool);
            }

            if(!is_null($request->septic) && in_array($request->septic, $radioValues)){
                $buyers = $buyers->where('septic', $request->septic);
            }

            if(!is_null($request->well) && in_array($request->well, $radioValues)){
                $buyers = $buyers->where('well', $request->well);
            }

            if(!is_null($request->age_restriction) && in_array($request->age_restriction, $radioValues)){
                $buyers = $buyers->where('age_restriction', $request->age_restriction);
            }

            if(!is_null($request->rental_restriction) && in_array($request->rental_restriction, $radioValues)){
                $buyers = $buyers->where('rental_restriction', $request->rental_restriction);
            }

            if(!is_null($request->hoa) && in_array($request->hoa, $radioValues)){
                $buyers = $buyers->where('hoa', $request->hoa);
            }

            if(!is_null($request->tenant) && in_array($request->tenant, $radioValues)){
                $buyers = $buyers->where('tenant', $request->tenant);
            }

            if(!is_null($request->post_possession) && in_array($request->post_possession, $radioValues)){
                $buyers = $buyers->where('post_possession', $request->post_possession);
            }

            if(!is_null($request->building_required) && in_array($request->building_required, $radioValues)){
                $buyers = $buyers->where('building_required', $request->building_required);
            }

            if(!is_null($request->foundation_issues) && in_array($request->foundation_issues, $radioValues)){
                $buyers = $buyers->where('foundation_issues', $request->foundation_issues);
            }

            if(!is_null($request->mold) && in_array($request->mold, $radioValues)){
                $buyers = $buyers->where('mold', $request->mold);
            }

            if(!is_null($request->fire_damaged) && in_array($request->fire_damaged, $radioValues)){
                $buyers = $buyers->where('fire_damaged', $request->fire_damaged);
            }

            if(!is_null($request->rebuild) && in_array($request->rebuild, $radioValues)){
                $buyers = $buyers->where('rebuild', $request->rebuild);
            }

            if(!is_null($request->squatters) && in_array($request->squatters, $radioValues)){
                $buyers = $buyers->where('squatters', $request->squatters);
            }
            
            if($request->total_units){
                $buyers = $buyers->where('unit_min', '<=', $request->total_units)->where('unit_max' ,'>=',$request->total_units);
            }

            if($request->max_down_payment_percentage){
                $buyers = $buyers->where('max_down_payment_percentage', $request->max_down_payment_percentage);
            }

            if($request->max_down_payment_money){
                $buyers = $buyers->where('max_down_payment_money', $request->max_down_payment_money);
            }

            if($request->max_interest_rate){
                $buyers = $buyers->where('max_interest_rate', $request->max_interest_rate);
            }

            if(!is_null($request->balloon_payment) && in_array($request->balloon_payment, $radioValues)){
                $buyers = $buyers->where('balloon_payment', $request->balloon_payment);
            }

            if($request->building_class){
                $buyers = $buyers->whereJsonContains('building_class', intval($request->building_class));
            }

            if(!is_null($request->value_add) && in_array($request->value_add, $radioValues)){
                $buyers = $buyers->where('value_add', $request->value_add);
            }

            if($request->buyer_type){
                $buyers = $buyers->whereJsonContains('buyer_type', intval($request->buyer_type));
            }

            $totalRecord = $buyers->count();

            $buyers = $buyers->paginate(10);

            $insertLogRecords = $request->all();
            $insertLogRecords['user_id'] = $userId;

            if(isset($request->filterType) && $request->filterType == 'search_page' ){

                $insertLogRecords['country'] =  DB::table('countries')->where('id',$request->country)->value('name');
                $insertLogRecords['state']   =  DB::table('states')->where('id',$request->state)->value('name');
                $insertLogRecords['city']    =  DB::table('cities')->where('id',$request->city)->value('name');

                SearchLog::create($insertLogRecords);
            }

            DB::commit();

            //Return Success Response
            $responseData = [
                'status'        => true,
                'buyers'        => $buyers,
                'total_records' => $totalRecord
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
            $totalBuyers = Buyer::where('user_id', $userId)->count();
            $buyers = Buyer::where('user_id',$userId)->paginate($perPage);
        
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

        try {
            $import = new BuyersImport;
            Excel::import($import, $request->file('csvFile'));

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

            $checkToken = Token::where('user_id',$authUserId)->where(function($query){
                $query->where('token_expired_time', '<=', Carbon::now())
                ->orWhere('is_used',1);
            })->first();

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
            $redFlagRecord[0]['buyer_id'] = $request->buyer_id;
            $redFlagRecord[0]['reason'] = $request->reason;
           
            $authUser = auth()->user();
            $authUser->redFlagedBuyer()->sync($redFlagRecord);

            DB::commit();
            //Return Success Response
            $responseData = [
                'status'        => true,
                'message'       => 'Flag added successfully!',
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
}
