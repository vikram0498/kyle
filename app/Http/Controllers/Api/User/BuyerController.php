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
   
    public function uploadSingleBuyerDetails(StoreSingleBuyerDetailsRequest $request){
        DB::beginTransaction();
        try {
            $validatedData = $request->all();
            $validatedData['user_id'] = auth()->user()->id;

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
            return response()->json($responseData, 401);
        }
    }

    public function buyBoxSearch(Request $request){
        $validator = Validator::make($request->all(), [
            'property_type'  => 'required|int',
        ]);

        if($validator->fails()){
             //Error Response Send
             $responseData = [
                'status'        => false,
                'validation_errors' => $validator->errors(),
            ];
            return response()->json($responseData, 401);
        }

        DB::beginTransaction();
        try {


            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 401);
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
            return response()->json($responseData, 401);
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
