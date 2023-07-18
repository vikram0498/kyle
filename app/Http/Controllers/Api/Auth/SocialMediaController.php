<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SocialMediaController extends Controller
{
    public function handleGoogle(Request $request){
        try {
            $social_id = 'google_'.$request->sub;
            $isUser = User::where('email', $request->email)->where('social_id', $social_id)->first();
            if($isUser){
                $userAuthenticated = Auth::loginUsingId($isUser->id);
                if($userAuthenticated){
                    $accessToken = $isUser->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;

                    $responseData = [
                        'status'        => true,
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
                $firstName = $request->given_name ?? null;
                $lastName  = $request->family_name ?? null;
                $newUser = User::create([
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'name'       => $firstName.' '.$lastName,
                    'email'      => $request->email ?? null,
                    'email_verified_at'  => Carbon::now(),
                    'register_type' => 2,
                    'social_id'     => $social_id,
                    'social_json'   => json_encode($request->all()),
                ]);

                // Assign Reviewer Role
                $newUser->roles()->sync(2);

                Auth::login($newUser);

                $accessToken = $newUser->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;
                $responseData = [
                    'status'        => true,
                    'message'       => 'Login successfully!',
                    'access_token'  => $accessToken
                ];
                return response()->json($responseData, 200);
            }
        
        } catch (\Exception $e) {
            // dd($e->getMessage());
            $responseData = [
                'status'        => false,
                'message'       => 'Something went wrong!',
            ];
            return response()->json($responseData, 401);
        }
    }

    public function handleFacebook(Request $request){
        try {
            $social_id = 'facebook_'.$request->id;
            $isUser = User::where('social_id', $social_id)->first();
    
            if($isUser){
                $userAuthenticated = Auth::loginUsingId($isUser->id);
                if($userAuthenticated){
                    $accessToken = $isUser->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;

                    $responseData = [
                        'status'        => true,
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
                    'social_id'     => $social_id,
                    'social_json'   => json_encode($request->all()),
                ]);
                
                // Assign Reviewer Role
                $newUser->roles()->sync(2);

                Auth::login($newUser);

                $accessToken = $newUser->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;
                $responseData = [
                    'status'        => true,
                    'message'       => 'Login successfully!',
                    'access_token'  => $accessToken
                ];
                return response()->json($responseData, 200);
            }
        }catch (\Exception $e) {
            // dd($e->getMessage().' - '.$e->getLine());
            $responseData = [
                'status'        => false,
                'message'       => 'Something went wrong!',
            ];
            return response()->json($responseData, 401);
        }
    }


}
