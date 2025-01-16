<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 


class SocialMediaController extends Controller
{
    public function handleGoogle(Request $request){
        try {
            DB::beginTransaction();
            $social_id = 'google_'.$request->sub;
            $isUser = User::where('email', $request->email)->withTrashed()->first();
            if($isUser){
                if(!is_null($isUser->deleted_at)){
                    //Error Response Send
                    $responseData = [
                        'status'        => false,
                        'error'         => 'Your account has been deactivated!',
                    ];
                    return response()->json($responseData, 401);
                }

                if(!$isUser->is_active){
                    //Error Response Send
                    $responseData = [
                        'status'        => false,
                        'error'         => 'Your account has been blocked!',
                    ];
                    return response()->json($responseData, 401);
                }

                $userAuthenticated = Auth::loginUsingId($isUser->id);
                if($userAuthenticated){
                    $accessToken = $isUser->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;

                    if(isset($request->device_token)){
                        $userAuthenticated->device_token =  $request->device_token ? $request->device_token : null;
                    }

                    $userAuthenticated->is_online = config('constants.online_status.online');

                    $userAuthenticated->save();

                    DB::commit();

                    $userData = [
                        'first_name'   => $isUser->first_name ?? '',
                        'last_name'    => $isUser->last_name ?? '',
                        'profile_image'=> $isUser->profile_image_url ?? '',
                        'role'=> $isUser->roles()->first()->id ?? '',
                        'level_type'   => $isUser->level_type,
                        'credit_limit' => $isUser->credit_limit,
                        'is_verified'  => $isUser->is_buyer_verified ?? false,
                        'total_buyer_uploaded' => $isUser->buyers()->where('user_id','!=',auth()->user()->id)->count(),
                        'is_switch_role' => $isUser->is_switch_role,
                    ];

                    if($isUser->is_buyer){
                        $userData['is_super_buyer'] = $isUser->is_super_buyer ? true : false;
                    }
                  

                    //Success Response Send
                    $responseData = [
                        'status'            => true,
                        'message'           => 'You have logged in successfully!',
                        'userData'          => $userData,
                        'remember_me_token' => $isUser->remember_token,
                        'access_token'      => $accessToken
                    ];
                    return response()->json($responseData, 200);
                }else{
                    $responseData = [
                        'status'        => false,
                        'error'         => 'Something went wrong!',
                    ];
                    return response()->json($responseData, 401);
                }
            }else{
                $firstName = $request->given_name ?? null;
                $lastName  = $request->family_name ?? null;
                $newUser = User::create([
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'name'       => $firstName.' '.$lastName,
                    'email'      => $request->email ?? null,
                    'email_verified_at'  => Carbon::now(),
                    'register_type' => 2,
                    'is_online'     => config('constants.online_status.online'),
                    'social_id'     => $social_id,
                    'social_json'   => json_encode($request->all()),
                    'device_token'  => isset($request->device_token) ? $request->device_token : null,
                ]);

                // Assign Reviewer Role
                $newUser->roles()->sync(2);

                //Start Store as buyers
                $buyerData['user_id']       = $newUser->id;
                $buyerData['buyer_user_id'] = $newUser->id;
                $buyerData['country']       =  DB::table('countries')->where('id', 233)->value('name');
                $newUser->buyerVerification()->create(['user_id' => $buyerData['buyer_user_id']]);
                $newUser->buyerDetail()->create($buyerData);
                //End Store as buyers

                Auth::login($newUser);

                $accessToken = $newUser->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;

                DB::commit();
                //Success Response Send
                $responseData = [
                    'status'            => true,
                    'message'           => 'You have logged in successfully!',
                    'userData'          => [
                        'first_name'   => $newUser->first_name ?? '',
                        'last_name'    => $newUser->last_name ?? '',
                        'profile_image'=> $newUser->profile_image_url ?? '',
                        'role'=> $newUser->roles()->first()->id ?? '',
                        'level_type'   => $newUser->level_type,
                        'credit_limit' => $newUser->credit_limit,
                        'is_verified'  => $newUser->is_buyer_verified ?? false,
                        'total_buyer_uploaded' => $newUser->buyers()->where('user_id','!=',auth()->user()->id)->count(),
                        'is_switch_role' => $newUser->is_switch_role,

                    ],
                    'remember_me_token' => $newUser->remember_token,
                    'access_token'      => $accessToken
                ];
                return response()->json($responseData, 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            //  dd($e->getMessage());
            $responseData = [
                'status'        => false,
                'message'       => 'Something went wrong!',
                'error_details' => $e->getMessage()
            ];
            return response()->json($responseData, 401);
        }
    }

    public function handleFacebook(Request $request){
        try {
            DB::beginTransaction();
            $social_id = 'facebook_'.$request->id;
            $isUser = User::where('social_id', $social_id)->orWhere('email',$request->email)->withTrashed()->first();
    
            if($isUser){
                if(!is_null($isUser->deleted_at)){
                    //Error Response Send
                    $responseData = [
                        'status'        => false,
                        'error'         => 'Your account has been deactivated!',
                    ];
                    return response()->json($responseData, 401);
                }

                if(!$isUser->is_active){
                    //Error Response Send
                    $responseData = [
                        'status'        => false,
                        'error'         => 'Your account has been blocked!',
                    ];
                    return response()->json($responseData, 401);
                }
                
                $userAuthenticated = Auth::loginUsingId($isUser->id);
                if($userAuthenticated){
                    $accessToken = $isUser->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;

                    if(isset($request->device_token)){
                        $userAuthenticated->device_token =  $request->device_token ? $request->device_token : null;
                    }

                    $userAuthenticated->is_online = config('constants.online_status.online');

                    $userAuthenticated->save();

                    DB::commit();

                    $userData = [
                        'first_name'   => $isUser->first_name ?? '',
                        'last_name'    => $isUser->last_name ?? '',
                        'profile_image'=> $isUser->profile_image_url ?? '',
                        'level_type'   => $isUser->level_type,
                        'credit_limit' => $isUser->credit_limit,
                        'total_buyer_uploaded' => $isUser->buyers()->where('user_id','!=',auth()->user()->id)->count(),
                        'is_switch_role' => $isUser->is_switch_role,
                    ];

                    if($isUser->is_buyer){
                        $userData['is_super_buyer'] = $isUser->is_super_buyer ? true : false;
                    }

                    $responseData = [
                        'status'        => true,
                        'userData'      => $userData,
                        'message'       => 'Login successfully!',
                        'access_token'  => $accessToken
                    ];
                    return response()->json($responseData, 200);
                }else{
                    $responseData = [
                        'status'        => false,
                        'error'         => 'Something went wrong!',
                    ];
                    return response()->json($responseData, 401);
                }
            }else{
               
                $name = explode(' ',$request->name);
                $newUser = User::create([
                    'first_name' => $name[0] ?? null,
                    'last_name'  => $name[1] ?? null,
                    'name'       => $request->name ?? null,
                    'email'      => $request->email ?? null,
                    'email_verified_at'  => (isset($request->email) && !is_null($request->email)) ? Carbon::now() : null,
                    'register_type' => 3,
                    'is_online'     => config('constants.online_status.online'),
                    'social_id'     => $social_id,
                    'social_json'   => json_encode($request->all()),
                    'device_token'  => isset($request->device_token) ? $request->device_token : null,
                ]);
                
                // Assign Reviewer Role
                $newUser->roles()->sync(2);

                //Start Store as buyers
                $buyerData['user_id']       = $newUser->id;
                $buyerData['buyer_user_id'] = $newUser->id;
                $buyerData['country']       =  DB::table('countries')->where('id', 233)->value('name');
                $newUser->buyerVerification()->create(['user_id' => $buyerData['buyer_user_id']]);
                $newUser->buyerDetail()->create($buyerData);
                //End Store as buyers


                Auth::login($newUser);

                $accessToken = $newUser->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;
                
                DB::commit();
                $responseData = [
                    'status'        => true,
                    'userData'      => [
                        'first_name'   => $newUser->first_name ?? '',
                        'last_name'    => $newUser->last_name ?? '',
                        'profile_image'=> $newUser->profile_image_url ?? '',
                        'level_type'   => $newUser->level_type,
                        'credit_limit' => $newUser->credit_limit,
                        'total_buyer_uploaded' => $newUser->buyers()->where('user_id','!=',auth()->user()->id)->count(),
                        'is_switch_role' => $newUser->is_switch_role,
                    ],
                    'message'       => 'Login successfully!',
                    'access_token'  => $accessToken
                ];
                return response()->json($responseData, 200);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().' - '.$e->getLine());
            $responseData = [
                'status'        => false,
                'message'       => 'Something went wrong!',
                'error_details' => $e->getMessage().' - '.$e->getLine()
            ];
            return response()->json($responseData, 401);
        }
    }


}
