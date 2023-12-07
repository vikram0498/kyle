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
            $query->where('is_phone_verification', 1)
            ->where(function ($sub_query) {
                $sub_query->where([
                    ['is_driver_license', 1],
                    ['driver_license_status', 'pending']
                ])
                ->orWhere([
                    ['is_proof_of_funds', 1],
                    ['proof_of_funds_status', 'pending']
                ])
                ->orWhere([
                    ['is_llc_verification', 1],
                    ['llc_verification_status', 'pending']
                ]);
            })
            ->where('is_application_process', 0);
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
