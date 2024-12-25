<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Plan;
use App\Models\Addon;
use App\Models\Video;
use App\Models\User;
use App\Models\Reason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupportRequest;
use App\Models\BuyerDeal;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

class HomeController extends Controller
{
   public function getPlans(){
        $plans = Plan::where('status',1)->orderBy('position')->get();
        //Success Response Send
        $responseData = [
            'status'   => true,
            'data'     => ['plans' => $plans]
        ];
        return response()->json($responseData, 200);
   }

   public function getAdditionalCredits(){
        $additionalCredits = Addon::where('status',1)->orderBy('position')->get();
        //Success Response Send
        $responseData = [
            'status'   => true,
            'data'     => ['additional_credits' => $additionalCredits]
        ];
        return response()->json($responseData, 200);
    }

    public function getVideo($key){
        try{
            $groupExists = getSettingGroupDetail('upload_buyer_video');
            if( $groupExists ){

                $videoDetails['title']       = getSetting('buyer_video_title');
                $videoDetails['sub_title']   = getSetting('buyer_video_sub_title');
                $videoDetails['description'] = getSetting('buyer_video_description');
                $videoDetails['video_link']  = getSetting('buyer_video');

                //Success Response Send
                $responseData = [
                    'status'          => true,
                    'videoDetails'    => ['is_active'=> true,'video' => $videoDetails]
                ];
                return response()->json($responseData, 200);
            }else{
                //Success Response Send
                $responseData = [
                    'status'          => true,
                    'videoDetails'    => ['is_active'=> false,'video' => '']
                ];
                return response()->json($responseData, 200);
            }
            
        }catch (\Exception $e) {
            //  dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 400);
        }
       
    }

    public function checkUserTokenExpired()
    {
        try {
            // Get the authenticated user
            $user = Auth::user();

            // Check if the user is authenticated
            if ($user) {
                // Get the user's access token
                $accessToken = $user->token();

                // Check if the access token is expired
                if ($accessToken->expires_at < now()) {
                    //Return Error Response
                    $responseData = [
                        'status'        => false,
                        'error'         => trans('Token Expired'),
                    ];
                    return response()->json($responseData, 401);
                } else {
                    $responseData = [
                        'status'        => true,
                        'message'         => trans('Token is not expired.'),
                    ];
                    return response()->json($responseData, 200);
                }
            } else {
                $responseData = [
                    'status'        => false,
                    'error'         => trans('Token Expired'),
                ];
                return response()->json($responseData, 401);
            }
        }catch (\Exception $e) {
            //  dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 400);
        }
    }

    public function isUserStatus(Request $request){
        $request->validate([
            'user_id'=>'required',
        ]);

        $checkUser = User::where('id',$request->user_id)->withTrashed()->first();
      
        if($checkUser){
            //Success Response Send
            $responseData = [
                'status'         => true,
                'user_status'    => is_null($checkUser->deleted_at) ? true : false,
                'is_buyer_verified'    => $checkUser->is_buyer_verified,

            ];
            return response()->json($responseData, 200);
        }else{
             //Error Response Send
             $responseData = [
                'status'         => false,
                'message'        => 'Something went wrong!'
            ];
            return response()->json($responseData, 400);
        }
       
    }


    public function getReasons(){
        $reasons = Reason::all();
        //Success Response Send
        $responseData = [
            'status'   => true,
            'data'     => $reasons->makeHidden(['created_at','updated_at'])
        ];
        return response()->json($responseData, 200);
    }

    public function getBuyerDashBoardDetail()
    {
        $totalDealsCount = 0;
        $unreadMessageCount = 0;
        try{           

            $user = auth()->user();
            $propertyTypes = config('constants.property_types');

            // Total number of buyer's deals 
            $buyerDealQuery = BuyerDeal::where("buyer_user_id", $user->id);

            $totalDealsCount = $buyerDealQuery->count();

            // Latest 3 Buyer Deals
            $latestDeals  = $buyerDealQuery->with(['searchLog', 'createdBy'])->latest()->take(3)->get();

            $latestDeals->transform(function ($buyerDeal) use ($propertyTypes) {
                $searchLog = $buyerDeal->searchLog ?? null;
                $propertType = $searchLog && $searchLog->property_type && $propertyTypes[$searchLog->property_type] ? $propertyTypes[$searchLog->property_type] : '';
                $address = $searchLog && $searchLog->address ? $searchLog->address : '';
                $is_proof_of_fund_verified = $buyerDeal->buyerUser->buyerVerification()->where('is_proof_of_funds', 1)->where('proof_of_funds_status','verified')->exists();
                return [
                    'id'                => $buyerDeal->id,
                    'sender_by'         => $searchLog->user_id,
                    'search_log_id'     => $searchLog->id ?? '',
                    'title'             => $address,
                    'address'           => $address,
                    'property_type'     => $propertType,
                    'property_images'   => $searchLog && $searchLog->uploads ? $searchLog->search_log_image_urls : '',
                    'picture_link'      => $searchLog && $searchLog->picture_link ? $searchLog->picture_link : '',
                    'status'            => $buyerDeal->status,
                    'is_proof_of_fund_verified'  => $is_proof_of_fund_verified,
                    'bedroom_min'       => $searchLog->bedroom,
                    'bath'              => $searchLog->bath,
                    'size'              => $searchLog->size,
                    'lot_size'          => $searchLog->lot_size,
                    'price'             => $searchLog->price,
                ];
            });  

            // Profile Verification status
            $verification = $user->buyerVerification;         

            $steps = [
                $verification->is_phone_verification ?? 0,
                $verification->is_driver_license && $verification->driver_license_status === 'verified',
                $verification->is_proof_of_funds && $verification->proof_of_funds_status === 'verified',
                $verification->is_llc_verification && $verification->llc_verification_status === 'verified',
                $verification->is_certified_closer && $verification->certified_closer_status === 'verified',
                $verification->is_application_process ?? 0,
            ];

            $totalSteps = count($steps);
            $completedSteps = collect($steps)->filter()->count();
            $percentage = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100, 2) : 0;

            $buyer_verification = [
                'completed_steps'   => $completedSteps,
                'total_steps'       => $totalSteps,
                'ratio'             => $completedSteps ." / " . $totalSteps,
                'percentage'        => $percentage . '%',
            ];

            // Total number of new chat messages
            $unreadMessageCount = Message::where('receiver_id', $user->id)
            ->where('sender_id', '!=', $user->id)
            ->whereDoesntHave('seenBy', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->count();

            $responseData = [
                'status'    => true,
                'data'      => [
                    'total_deals_count'     => $totalDealsCount,
                    'buyer_verification'     => $buyer_verification,                    
                    'unreadMessageCount'    => $unreadMessageCount,
                    'latest_buyer_deals'    => $latestDeals,
                ],
            ];
            return response()->json($responseData, 200);
    
        }catch(\Exception $e){
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
		        'error_details' => $e->getMessage().'->'.$e->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }


}
