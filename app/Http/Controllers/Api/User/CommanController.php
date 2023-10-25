<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;


class CommanController extends Controller
{
   
    public function getCities(Request $request){
        // $country_id = $request->country_id;
        $country_id = 233;
        $state_id   = $request->state_id;

        //Return Success Response
        $cities = DB::table('cities')->where('country_id',$country_id)->whereIn('state_id',$state_id)->orderBy('name','ASC')->pluck('name','id');

        $options = $cities->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => ucwords(strtolower($label)),
            ];
        })->values()->all();
    
        return response()->json(['options'=>$options], 200);
    }

  
}