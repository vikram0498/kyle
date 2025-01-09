<?php

namespace App\Http\Controllers\Api\User;

use Carbon\Carbon;
use App\Models\Buyer;
use App\Models\User;
use App\Models\UserBuyerLikes;
use App\Models\PurchasedBuyer;
use App\Models\BuyerInvitation;
use App\Models\Token;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCopyBuyerRequest;
use App\Http\Requests\StoreAddBuyerRequest;
use Illuminate\Support\Facades\Cache;

class CopyBuyerController extends Controller
{
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


    public function copyBuyerFormElementValues(){
        $elementValues = [];

        try{

            if (Cache::has('copyFormElementDetails')){
                $responseData = [
                    'status'        => true,
                    'result'        => Cache::get('copyFormElementDetails'),
                ];
                return response()->json($responseData, 200);
            }else{

                $elementValues['market_preferances'] = collect(config('constants.market_preferances'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => $label,
                    ];
                })->values()->all();               
                
                $elementValues['property_types'] = collect(config('constants.property_types'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => $label,
                    ];
                })->values()->all();

                $elementValues['park'] = collect(config('constants.park'))->map(function ($label, $value) {
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

                $elementValues['contact_preferances'] = collect(config('constants.contact_preferances'))->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => ucwords(strtolower($label)),
                    ];
                })->values()->all();

                $states = DB::table('states')->where('country_id',233)->where('flag','=',1)->orderBy('name','ASC')->pluck('name','id');

                $elementValues['states'] = $states->map(function ($label, $value) {
                    return [
                        'value' => $value,
                        'label' => ucwords(strtolower($label)),
                    ];
                })->values()->all();

		$elementValues['links']['terms_services_link'] = getSetting('terms_services_link');
		$elementValues['links']['privacy_policy_link'] = getSetting('privacy_policy_link');

                Cache::put('copyFormElementDetails',$elementValues);

                //Return Success Response
                $responseData = [
                    'status'        => true,
                    'result'        => $elementValues,
                ];
                return response()->json($responseData, 200);
            }

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

    public function uploadCopyBuyerDetails(StoreCopyBuyerRequest $request,$token){
        try {
            DB::beginTransaction();
            
            //Start to check phone number verified
           if(!isPhoneNumberVerified($request->country_code,$request->phone)){
                $responseData = [
                    'status'        => false,
                    'message'       => 'OTP not verified.',
                ]; 
                return response()->json($responseData, 403);
            }
            //End to check phone number verified

            $isMailSend = false;
            $validatedData = $request->all();

            // Start token functionality
            if($token){
                $checkToken = Token::where('token_value',$token)->first();
                if($checkToken){
                    if(($checkToken->is_used === 1) && ($request->type == 'private-buyer')){
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
                     //Return Error Response
                     $responseData = [
                        'status'        => false,
                        'error_type'    => 'token_error',
                        'error'         => 'Invalid Token!',
                    ];
                    return response()->json($responseData, 400);
                }
               
            }
            // End token functionality

            // Start create users table
            $userDetails =  [
                'first_name'     => $validatedData['first_name'],
                'last_name'      => $validatedData['last_name'],
                'name'           => ucwords($validatedData['first_name'].' '.$validatedData['last_name']),
                'email'          => $validatedData['email'], 
                'country_code'   => $validatedData['country_code'],
                'phone'          => $validatedData['phone'], 
            ];

            $findUser = User::where('email',$userDetails['email'])->first();
            if($findUser){
                $createUser = $findUser;
                User::where('id',$findUser->id)->update($userDetails);
            }else{
                $createUser = User::create($userDetails);
                $createUser->roles()->sync(3);
            }
            // End create users table

            if($createUser){

                $validatedData['buyer_user_id'] = $createUser->id;

                $validatedData['country'] =  DB::table('countries')->where('id',233)->value('name');

                if($request->state){
                     $validatedData['state'] = json_encode($request->state);
                }
                
                 if($request->city){
                     $validatedData['city'] = json_encode($request->city);
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
            
                if($request->permanent_affix){
                    $validatedData['permanent_affix'] = (int)$request->permanent_affix;
                } 
                if($request->park){
                    $validatedData['park'] = (int)$request->park;
                }  
                if($request->rooms){
                    $validatedData['rooms'] = (int)$request->rooms;
                }
                
                
                // $createdBuyer = Buyer::create($validatedData);

                if(!$findUser){
                    $createUser->buyerVerification()->create(['user_id'=>$validatedData['user_id']]);
                }

                $validatedData = collect($validatedData)->except(['first_name', 'last_name','email','phone'])->all();
                
                $createUser->buyerDetail()->create($validatedData);
                
                if(!$findUser){
                    if($token){
                        //Purchased buyer
                        $syncData['buyer_id'] = $createUser->buyerDetail->id;
                        $syncData['created_at'] = Carbon::now();
                
                        $createUser->purchasedBuyers()->create($syncData);

                        $isMailSend = true;
                    }else{
                        if(auth()->user()->is_seller){
                            //Purchased buyer
                            $syncData['buyer_id'] = $createUser->buyerDetail->id;
                            $syncData['created_at'] = Carbon::now();
                    
                            auth()->user()->purchasedBuyers()->create($syncData);
                        }

                        $isMailSend = true;
                    }
                    
                    if($isMailSend){
                        //Verification mail sent
                        $createUser->NotificationSendToBuyerVerifyEmail();

                        sendNotificationToAdmin($createUser, 'new_user_register');

                        $checkToken = Token::where('token_value',$token)->first();
                        if($checkToken){
                            sendNotificationToUser($checkToken->user, $createUser, 'new_user_register','new_buyer_notification');
                        }

                    }
                }else{
                    
                    $syncData['buyer_id'] = $createUser->buyerDetail->id;
                    $syncData['created_at'] = Carbon::now();
                    $createUser->purchasedBuyers()->create($syncData);

                }

                if($token && ($request->type == 'private-buyer')){
                    Token::where('token_value',$token)->update(['is_used'=>1]);
                }
            }

            //Clear OTP Cache
            forgetOtpCache($request->country_code,$request->phone);

            DB::commit();
                
            //Success Response Send
            $responseData = [
                'status'            => true,
                'message'           => trans('messages.auth.buyer.register_success_alert'),
            ];
            return response()->json($responseData, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            //  dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
                'error_details' => $e->getMessage().'->'.$e->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }

    public function isValidateToken($type,$token){
        $tokenExpired = $this->checkTokenValidate($type,$token);

        if($type == 'private-buyer'){
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
        }elseif($type == 'public-buyer'){
            if($tokenExpired){
                //Success Response Send
                $responseData = [
                    'status'            => true,
                    'message'           => 'Token is validate!',
                ];
                return response()->json($responseData, 200);
            }
        }

    }

    private function checkTokenValidate($type,$token){
        $currentDateTime = Carbon::now();
        $tokenExpired = false;
        
        if($type == 'private-buyer'){
            $checkToken = Token::where('token_value',$token)->where('is_used',1)->first();
            if($checkToken){
                $tokenExpired = true;
                // if($checkToken->token_expired_time > $currentDateTime) {
                //     $tokenExpired = false;
                // }
            }
        }elseif($type == 'public-buyer'){
            $checkToken = Token::where('token_value',$token)->first();
            if($checkToken){
                $tokenExpired = true;
            }
        }
       

        return $tokenExpired;
    }


    public function addBuyer(StoreAddBuyerRequest $request){
        try {
            DB::beginTransaction();

            //Start to check phone number verified
            /*if(!isPhoneNumberVerified($request->country_code,$request->phone)){
                $responseData = [
                    'status'        => false,
                    'message'       => 'OTP not verified.',
                ]; 
                return response()->json($responseData, 403);
            }*/
            //End to check phone number verified

            $validatedData = $request->all();

            $superAdminUser = User::whereHas('roles',function($query){
                $query->where('id',config('constants.roles.super_admin'));
            })->first();

            $validatedData['user_id'] = $superAdminUser->id;

            //Start Register by invitation link
            $buyerRegisteredByInvitationLink = false;
            if($request->uuid){
                $buyerInvitation = BuyerInvitation::where('uuid',$request->uuid)->first();
                if($buyerInvitation){
                    $validatedData['user_id'] = $buyerInvitation->createdBy()->withTrashed()->value('id');
                    $validatedData['email'] = $buyerInvitation->email;
                    $buyerRegisteredByInvitationLink = true;
                }
            }
            //End Register by invitation link


            // Start create users table
            $userDetails =  [
                'first_name'     => $validatedData['first_name'],
                'last_name'      => $validatedData['last_name'],
                'name'           => ucwords($validatedData['first_name'].' '.$validatedData['last_name']),
                'email'          => $validatedData['email'],
                'country_code'   => $validatedData['country_code'], 
                'phone'          => $validatedData['phone'], 
            ];
            $createUser = User::create($userDetails);
            // End create users table

            if($createUser){

                $createUser->roles()->sync(3);

                $validatedData['buyer_user_id'] = $createUser->id;

                $validatedData['country'] =  DB::table('countries')->where('id',233)->value('name');

                if($request->state){
                     $validatedData['state'] = json_encode($request->state);
                }
                
                 if($request->city){
                     $validatedData['city'] = json_encode($request->city);
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
            
                if($request->permanent_affix){
                    $validatedData['permanent_affix'] = (int)$request->permanent_affix;
                } 
                if($request->park){
                    $validatedData['park'] = (int)$request->park; 
                }  
                if($request->rooms){
                    $validatedData['rooms'] = (int)$request->rooms;
                }
                
                $createUser->buyerVerification()->create(['user_id' => $validatedData['user_id']]);
                $validatedData = collect($validatedData)->except(['uuid','first_name', 'last_name','email','country_code','phone','g-recaptcha-response'])->all();
                $createUser->buyerDetail()->create($validatedData);

                //Purchased buyer
                $syncData['buyer_id']   = $createUser->buyerDetail->id;
                $syncData['created_at'] = Carbon::now();
        
                User::where('id',$validatedData['user_id'])->first()->purchasedBuyers()->create($syncData);

                //Verification mail sent
                $createUser->NotificationSendToBuyerVerifyEmail();

                sendNotificationToAdmin($createUser, 'new_user_register');
            }

            //Start Register by invitation link
            if($buyerRegisteredByInvitationLink){
                BuyerInvitation::where('uuid',$request->uuid)->update(['status'=>1]);
            }
            //End Register by invitation link

            //Clear OTP Cache
            forgetOtpCache($request->country_code,$request->phone);

            DB::commit();
                
            //Success Response Send
            $responseData = [
                'status'            => true,
                'message'           => trans('messages.auth.buyer.register_success_alert'),
            ];
            return response()->json($responseData, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            //  dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
                'error_details' => $e->getMessage().'->'.$e->getLine(),
            ];
            return response()->json($responseData, 500);
        }
    }

    public function checkBuyerInvitationLink(Request $request){

        if($request->uuid){
            $buyerInvitation = BuyerInvitation::where('uuid',$request->uuid)->where('status',0)->first();

            //Success Response Send
            $responseData = [
                'status'            => true,
                'is_valid'          => false,
            ];
    
            if($buyerInvitation){
                $responseData['is_valid'] = true;
                $responseData['data']['email'] = $buyerInvitation->email;
            }
        }else{
            $responseData = [
                'status'            => true,
                'is_valid'          => null,
            ];
        }

        return response()->json($responseData, 200);
    }
}
