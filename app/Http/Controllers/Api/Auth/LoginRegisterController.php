<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon; 
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LoginRegisterController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name'                => 'required',
            'last_name'                 => 'required',
            'email'                     => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'phone'                     => 'required|numeric|not_in:-|unique:users,phone,NULL,id,deleted_at,NULL',
            // 'address'                   => 'required',
            'company_name'              => 'required',
            'password'                  => 'required|min:8',
            'password_confirmation'     => 'required|same:password',
        ],[
            'phone.required'=>'The mobile number field is required',
            'phone.digits' =>'The mobile number must be 10 digits',
            'phone.unique' =>'The mobile number already exists.',
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
            $input = $request->all();
            $input['name'] = $input['first_name'].' '.$input['last_name'];
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);

            //Verification mail sent
            $user->NotificationSendToVerifyEmail();

            $user->roles()->sync(2);
            
            DB::commit();

            //Success Response Send
            $responseData = [
                'status'        => true,
                'message'       => 'Register successfully!',
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

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email'             => 'required|email',
            'password'          => 'required|min:8'
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

            $remember_me = !is_null($request->remember) ? true : false;
            $credentialsOnly = [
                'email'    => $request->email,
                'password' => $request->password,
            ]; 


            if(Auth::attempt($credentialsOnly, $remember_me)){
                $user = User::find(Auth::id());
                if(is_null($user->email_verified_at)){
                    $user->NotificationSendToVerifyEmail();

                    //Error Response Send
                    $responseData = [
                        'status'        => false,
                        'error'         => 'Your account is not verified! Please check your mail',
                    ];
                    return response()->json($responseData, 401);
                }

                $accessToken = $user->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;

                DB::commit();

                //Success Response Send
                $responseData = [
                    'status'            => true,
                    'message'           => 'You have logged in successfully!',
                    'userData'          => [
                        'first_name'   => $user->first_name ?? '',
                        'last_name'    => $user->last_name ?? '',
                        'profile_image'=> $user->profile_image_url ?? '',
                        'level_type'   => $user->level_type,
                        'credit_limit' => $user->credit_limit,
                        'total_buyer_uploaded' => $user->purchasedBuyers()->count(),
                    ],
                    'remember_me_token' => $user->remember_token,
                    'access_token'      => $accessToken
                ];
                return response()->json($responseData, 200);

            } else{

                //Error Response Send
                $responseData = [
                    'status'        => false,
                    'error'         => 'These credentials do not match our records!',
                ];
                return response()->json($responseData, 401);
            }
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

    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
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
            $token = Str::random(64);
            $email_id = $request->email;
            $user = User::where('email', $email_id)->first();

            $userDetails = array();
            $userDetails['name'] = ucwords($user->first_name.' '.$user->last_name);

            $userDetails['reset_password_url'] = env('FRONTEND_URL').'reset-password/'.$token.'/'.encrypt($email_id);

            // $userDetails['reset_password_url'] = 'https://kyle-react.hipl-staging3.com/reset-password/'.$token.'/'.encrypt($email_id);

        
            
            DB::table('password_resets')->insert([
                'email'         => $email_id, 
                'token'         => $token, 
                'created_at'    => Carbon::now()
            ]);

            $subject = 'Reset Password Notification';
            Mail::to($email_id)->queue(new ResetPasswordMail($userDetails['name'],$userDetails['reset_password_url'], $subject));

            DB::commit();

            //Success Response Send
            $responseData = [
                'status'        => true,
                'message'         => __('passwords.sent'),
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

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'password'                  => 'required|min:8|confirmed',
            'password_confirmation'     => 'required',
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
            $token = $request->token;
            $email = decrypt($request->hash);

            $updatePassword = DB::table('password_resets')->where(['email' => $email,'token' => $token])->first();

            if(!$updatePassword){
                //Error Response Send
                $responseData = [
                    'status'        => false,
                    'error'         => trans('passwords.token'),
                ];
                return response()->json($responseData, 401);

            }else{

                $user = User::where('email', $email)
                ->update(['password' => bcrypt($request->password)]);

                DB::table('password_resets')->where(['email'=> $email])->delete();

                DB::commit();

                //Success Response Send
                $responseData = [
                    'status'  => true,
                    'message' => __('passwords.reset'),
                ];
                return response()->json($responseData, 200);

            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 401);
        }
    }

    public function verifyEmail($id, $hash){
        $user = User::find($id);
        
        if(!is_null($user->email_verified_at)){
            
            $responseData = [
                'status'  => true,
                'message' => 'Email is aleready verifed!',
            ];
            return response()->json($responseData, 200);
        }
        if ($user && $hash === sha1($user->email)) {
            $user->update(['email_verified_at' => date('Y-m-d H:i:s')]);

            //Success Response Send
            $responseData = [
                'status'  => true,
                'message' => 'Email Verified successfully!',
            ];
            return response()->json($responseData, 200);
        }

        // Error Response Send
        $responseData = [
            'status'  => false,
            'error'   => 'Mail verification failed!',
        ];
        return response()->json($responseData, 401);
    }
}
