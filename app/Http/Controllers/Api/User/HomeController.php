<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Plan;
use App\Models\Addon;
use App\Models\Video;
use App\Models\Support;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupportRequest;
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
            $video = Video::where('video_key',$key)->where('status',1)->first();
            $video->video_link = $video->video_url;
            //Success Response Send
            $responseData = [
                'status'          => true,
                'videoDetails'    => ['video' => $video]
            ];
            return response()->json($responseData, 200);
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
    public function support(SupportRequest $request){
        try{
            DB::beginTransaction();
            $validatedData['name'] = $request->name;
            $validatedData['email'] = $request->email;
            $validatedData['message'] = $request->message;
            $createdBuyer = Support::create($validatedData);
            $responseData = [
                'status'        => true,
                'success'       => "Request submitted successfully",
            ];
            DB::commit();
            return response()->json($responseData, 200);
        }catch(\Exception $e){
            DB::rollBack();
            $responseData = [
                'status'        => false,
                'error'         => $e->getMessage(),
            ];
            return response()->json($responseData, 400);
        }
    }
}
