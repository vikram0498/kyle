<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon; 
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Mail\ResetPasswordMail;
use App\Services\TwilioService;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendNotification;
use App\Models\BuyerInvitation;


class LoginRegisterController extends Controller
{
    public function register(Request $request){
        $rules = [
            'first_name'                => 'required',
            'last_name'                 => 'required',
            // 'email'                     => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            // 'phone'                     => 'required|numeric|not_in:-|unique:users,phone,NULL,id,deleted_at,NULL',
            'email'                     => 'required|email|regex:/^(?!.*[\/]).+@(?!.*[\/]).+\.(?!.*[\/]).+$/i|unique:users,email,NULL,id',
            // 'phone'                     => 'required|numeric|not_in:-|unique:users,phone,NULL,id',
            // 'address'                   => 'required',
            'company_name'              => 'required',
            'password'                  => 'required|min:8',
            'password_confirmation'     => 'required|same:password',
	        'terms_accepted'  		    => 'required', 
        ];

        $rules['country_code'] = ['required', 'numeric'];
        $rules['phone'] = [
            'required', 'numeric','digits:10','not_in:-',
            Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('country_code', $request->country_code);
            })
        ];

        $validator = Validator::make($request->all(), $rules, [
            'phone.required' => 'The mobile number field is required',
            'phone.digits'   => 'The mobile number must be 10 digits',
            'phone.unique'   => 'The mobile number already exists.',
        ]);
        
        if($validator->fails()){
            //Error Response Send
            $responseData = [
                'status'        => false,
                'validation_errors' => $validator->errors(),
            ];
            return response()->json($responseData, 401);
        }

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

            $input = $request->except(['terms_accepted','password_confirmation']);  
            $input['name'] = $input['first_name'].' '.$input['last_name'];
            $input['password'] = bcrypt($input['password']);
            // $input['phone_verified_at'] = now();

            $user = User::create($input);

            if($user){
                //Start Store as buyers
                $buyerData['user_id']       = $user->id;
                $buyerData['buyer_user_id'] = $user->id;
                $buyerData['country']       =  DB::table('countries')->where('id', 233)->value('name');
                $user->buyerVerification()->create(['user_id' => $buyerData['buyer_user_id']]);
                $user->buyerDetail()->create($buyerData);
                //End Store as buyers
            
                //Verification mail sent
                $user->NotificationSendToVerifyEmail();

                $user->roles()->sync(2);
                
                //Clear OTP Cache
                // forgetOtpCache($request->country_code,$request->phone);

                sendNotificationToAdmin($user, 'new_user_register');

                DB::commit();

                //Success Response Send
                $responseData = [
                    'status'        => true,
                    'message'       => 'Register successfully!',
                ];  
                return response()->json($responseData, 200);
            }

            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 404);

        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
                'error_details' => $e->getMessage().' - '.$e->getLine()
            ];
            return response()->json($responseData, 401);
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email'             => 'required|email|regex:/^(?!.*[\/]).+@(?!.*[\/]).+\.(?!.*[\/]).+$/i',
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

            $checkUserStatus = User::where('email',$request->email)->withTrashed()->first();

            if(!$checkUserStatus){
                //Error Response Send
                $responseData = [
                    'status'        => false,
                    'error'         => trans('auth.failed'),
                ];
                return response()->json($responseData, 401);
            }

            if($checkUserStatus->is_buyer || $checkUserStatus->is_seller){

                if($checkUserStatus){
                    if(!is_null($checkUserStatus->deleted_at)){
                        //Error Response Send
                        $responseData = [
                            'status'        => false,
                            'error'         => 'Your account has been deactivated!',
                        ];
                        return response()->json($responseData, 401);
                    }

                    if(!$checkUserStatus->is_active && $checkUserStatus->is_seller){
                        //Error Response Send
                        $responseData = [
                            'status'        => false,
                            'error'         => 'Your account has been blocked!',
                        ];
                        return response()->json($responseData, 401);
                    }

                    if($checkUserStatus->is_buyer && is_null($checkUserStatus->email_verified_at)){

                        $checkUserStatus->NotificationSendToBuyerVerifyEmail();

                        DB::commit();

                        //Error Response Send
                        $responseData = [
                            'status'        => false,
                            'error'         => 'Your account is not verified! Please check your mail',
                        ];
                        return response()->json($responseData, 401);
                    }

                    if($checkUserStatus->is_seller && is_null($checkUserStatus->email_verified_at)){

                        $checkUserStatus->NotificationSendToVerifyEmail();

                        DB::commit();
                        
                        //Error Response Send
                        $responseData = [
                            'status'        => false,
                            'error'         => 'Your account is not verified! Please check your mail',
                        ];
                        return response()->json($responseData, 401);
                    }

                }


                if(Auth::attempt($credentialsOnly, $remember_me)){
                    $user = Auth::user();

                    $user->is_online = config('constants.online_status.online');

                    $user->save();
                   
                    $accessToken = $user->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;

                    DB::commit();

                    //Success Response Send
                    $responseData = [
                        'status'            => true,
                        'message'           => 'You have logged in successfully!',
                        'userData'          => [
                            'id'           => $user->id,
                            'first_name'   => $user->first_name ?? '',
                            'last_name'    => $user->last_name ?? '',
                            'profile_image'=> $user->profile_image_url ?? '',
                            'role'=> $user->roles()->first()->id ?? '',
                            'level_type'   => $user->level_type,
                            'credit_limit' => $user->credit_limit,
                            'is_verified'  => $user->is_buyer_verified,
                            'total_buyer_uploaded' => $user->buyers()->where('user_id','!=',auth()->user()->id)->count(),
                            'is_switch_role' => $user->is_switch_role,
                        ],
                        'remember_me_token' => $user->remember_token,
                        'access_token'      => $accessToken
                    ];


                    if(isset($request->device_token)){
                        $user->device_token =  $request->device_token ? $request->device_token : null;
                    }
                    $user->login_at = now();
                    $user->save();
                    
                    return response()->json($responseData, 200);

                } else{

                    //Error Response Send
                    $responseData = [
                        'status'        => false,
                        'error'         => trans('auth.failed'),
                    ];
                    return response()->json($responseData, 401);
                }
                
            }else{
                 //Error Response Send
                 $responseData = [
                    'status'        => false,
                    'error'         => trans('auth.failed'),
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
                'error_details' => $e->getMessage().'->'.$e->getLine(),
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
            $user = User::where('email', $email_id)->withTrashed()->first();

            if(!is_null($user->deleted_at)){
                //Error Response Send
                $responseData = [
                    'status'        => false,
                    'error'         => 'Your account has been deactivated!',
                ];
                return response()->json($responseData, 401);
            }

            if(!$user->is_active){
                //Error Response Send
                $responseData = [
                    'status'        => false,
                    'error'         => 'Your account has been blocked!',
                ];
                return response()->json($responseData, 401);
            }

            $userDetails = array();
            $userDetails['name'] = ucwords($user->first_name.' '.$user->last_name);

            $userDetails['reset_password_url'] = env('FRONTEND_URL').'reset-password/'.$token.'/'.encrypt($email_id);
            
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
            return response()->json($responseData, 422);
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
            // dd($e->getMessage().'->'.$e->getLine());
            
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

            sendNotificationToAdmin($user, 'email_verified');

            //Success Response Send
            $responseData = [
                'status'  => true,
                'message' => 'Email verified successfully!',
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

   
    public function verifyBuyerEmailAndSetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'id'                        => 'required|numeric',
            'hash'                      => 'required',
            'password'                  => 'required|min:8',
            'password_confirmation'     => 'required|same:password',
        ]);

        if($validator->fails()){
            //Error Response Send
            $responseData = [
                'status'        => false,
                'validation_errors' => $validator->errors(),
            ];
            return response()->json($responseData, 422);
        }

        $user = User::find($request->id);
        
        if(!is_null($user->email_verified_at)){
            
            $responseData = [
                'status'  => true,
                'message' => 'Email is already verifed!',
            ];
            return response()->json($responseData, 200);
        }

        if ($user && $request->hash === sha1($user->email)) {
            $user->update(['password' => bcrypt($request->password),'email_verified_at' => date('Y-m-d H:i:s')]);

            sendNotificationToAdmin($user, 'email_verified');

            //Start Register by invitation link buyer verified email
            $buyerInvitation = BuyerInvitation::where('email',$user->email)->where('status',1)->first();
            if($buyerInvitation){
                sendNotificationToUser($buyerInvitation->createdBy, $user, 'email_verified','new_buyer_notification');
            }
            //End Register by invitation link buyer verified email


            //Success Response Send
            $responseData = [
                'status'  => true,
                'message' => 'Email verified and password set successfully!',
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

    public function getEmail($userId){
        $user = User::find($userId);
        if($user ){
            if(is_null($user->email_verified_at)){
                $responseData = [
                    'status'  => true,
                    'is_verify_email' => false,
                    'message' => 'Here your email',
                    'data'    => $user->email ?? '',
                ];
                return response()->json($responseData, 200);
            } else {
                //Return Error Response
                $responseData = [
                    'status'        => false,
                    'is_verify_email' => true,
                    'error'         => trans('Email is already verifed! Please login'),
                ];
                return response()->json($responseData, 422);
            }
        }{
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 401);
        }
        
    }

    public function getLinks(){
        $responseData['links']['terms_services_link'] = getSetting('terms_services_link');
        $responseData['links']['privacy_policy_link'] = getSetting('privacy_policy_link');

        //Return Success Response
        $response = [
            'status'        => true,
            'result'        => $responseData,
        ];
        return response()->json($response, 200);
    }

    public function sendOTPOnPhone(Request $request){
        $rules['country_code'] = ['required', 'numeric'];
        $rules['phone'] = [
            'required', 'numeric','digits:10','not_in:-',
            Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('country_code', $request->country_code);
            })
        ];

        $request->validate($rules);

        try {
            $otp = generateOTP();
            $fullPhoneNumber = $request->country_code.$request->phone;
            Cache::put('otp_' .$fullPhoneNumber, $otp, now()->addMinutes(config('constants.otp_time')));

            /*
            $twilio = new TwilioService;

            $toPhoneNumber = '+'.$fullPhoneNumber;
            $message = trans('messages.otp_sms_content',['otpNumber'=>$otp,'otpTime'=>config('constants.otp_time')]);
            $twilio->send_SMS($toPhoneNumber, $message);
            */

            //Return Success Response
            $responseData = [
                'status'        => true,
                'message'       => trans('messages.auth.verification.otp_send_success'),
                'otp'           => $otp
            ];
            return response()->json($responseData, 200);

        }catch (\Exception $e) {
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
		        'error_details' => $e->getMessage().'->'.$e->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }

    public function verifyOTP(Request $request){
        $rules['country_code'] = ['required', 'numeric'];
        $rules['phone'] = [
            'required', 'numeric','digits:10','not_in:-',
            Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('country_code', $request->country_code);
            })
        ];
        $rules['otp'] = ['required','numeric','digits:4','not_in:-'];
        $request->validate($rules,[
            'otp.required' => 'Please enter the OTP.',
            'otp.digits' => 'The OTP must be exactly 4 digits.',
        ],[]);

        try {
            $fullPhoneNumber = $request->country_code.$request->phone;
            $cachedOtp = Cache::get('otp_' . $fullPhoneNumber);

            if ($cachedOtp && $cachedOtp == $request->otp) {
                Cache::put('otp_verified_' . $fullPhoneNumber, true, now()->addMinutes(10));

                //Return Success Response
                $responseData = [
                    'status'        => true,
                    'message'       => trans('messages.auth.verification.phone_verify_success'),
                ];
                return response()->json($responseData, 200);
            }else{
                 //Return Success Response
                 $responseData = [ 
                    'status'        => true,
                    'message'       => trans('messages.auth.verification.invalid_otp'),
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
  


}
