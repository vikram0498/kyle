<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function getCountOfLatestKyc()
    {
        $kycBuyersCount = User::query()->whereHas('buyerVerification',function($query){
            $query->where('is_phone_verification', 0)
            ->orWhere('is_driver_license',0)->orWhere('driver_license_status','!=','verified')
            ->orWhere('is_proof_of_funds', 0)->orWhere('proof_of_funds_status','!=','verified')
            ->orWhere('is_llc_verification',0)->orWhere('llc_verification_status','!=','verified')
            ->orWhere('is_application_process',0);
        })
        ->whereHas('roles',function($query){
            $query->whereIn('id',[3]);
        })->count();

        // Return response
        $responseData = [
            'status'    => true,
            'kycBuyersCount'=>$kycBuyersCount
        ];
        return response()->json($responseData, 200);
    }
}
