<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Buyer;
use App\Imports\MultipleBuyerImport;
use Illuminate\Support\Arr;
use App\Rules\CheckMaxValue;
use App\Rules\CheckMinValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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

    public function uploadSingleBuyerDetails(StoreSingleBuyerDetailsRequest $request){
        DB::beginTransaction();
        try {
            $validatedData = $request->all();
            $validatedData['user_id'] = auth()->user()->id;

            $validatedData['country'] =  DB::table('countries')->where('id',$request->country)->value('name');

            $validatedData['state']   =  DB::table('states')->where('id',$request->state)->value('name');
            $validatedData['city']    =  DB::table('cities')->where('id',$request->city)->value('name');

            Buyer::create($validatedData);

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

    public function buyBoxSearch(Request $request){
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

            $userId = auth()->user()->id;
            $buyers = Buyer::where('user_id',$userId)->paginate(10);
        
            DB::commit();

            //Return Success Response
            $responseData = [
                'status'        => true,
                'buyers'        => $buyers,
            ];

            return response()->json($responseData, 400);

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
            $buyers = Buyer::where('user_id',$userId)->paginate($perPage);
        
            DB::commit();

            //Return Success Response
            $responseData = [
                'status'        => true,
                'buyers'        => $buyers,
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
        $import = new MultipleBuyerImport();
        $import->import($request->file('file'));

        if ($import->failures()->count() > 0) {
            //Return Error Response
            $responseData = [
                'status'        => false,
                'errors'        => $import->failures(),
            ];
            return response()->json($responseData, 400);
        } else {
            //Return Success Response
            $responseData = [
                'status'        => true,
                'message'       => 'Buyers imported successfully!',
            ];
            return response()->json($responseData, 200);
        }
    }

}
