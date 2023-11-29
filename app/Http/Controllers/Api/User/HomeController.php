<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Plan;
use App\Models\Addon;
use App\Models\Video;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupportRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

class HomeController extends Controller
{
   public function getPlans(){
        $plans = Plan::where('status',1)->get();
        //Success Response Send
        $responseData = [
            'status'   => true,
            'data'     => ['plans' => $plans]
        ];
        return response()->json($responseData, 200);
   }

   public function getAdditionalCredits(){
        $additionalCredits = Addon::where('status',1)->get();
        //Success Response Send
        $responseData = [
            'status'   => true,
            'data'     => ['additional_credits' => $additionalCredits]
        ];
        return response()->json($responseData, 200);
    }

    public function getVideo($key){
        try{
            $video = Video::where('video_key','upload_buyer_video')->where('status',1)->first();
            if( $video ){
                $video->video_link = $video->video_url;
                //Success Response Send
                $responseData = [
                    'status'          => true,
                    'videoDetails'    => ['is_active'=> true,'video' => $video]
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

}
