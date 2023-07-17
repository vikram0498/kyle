<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon; 
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;

class LoginRegisterController extends BaseController
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name'                => 'required',
            'last_name'                 => 'required',
            'email'                     => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'phone'                     => 'required|numeric|digits:10|unique:users,phone,NULL,id,deleted_at,NULL',
            // 'address'                   => 'required',
            'company_name'              => 'required',
            'password'                  => 'required|min:8|confirmed',
            'password_confirmation'     => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['name'] = $input['first_name'].' '.$input['last_name'];
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        //Verification mail sent
        $user->NotificationSendToVerifyEmail();

        $user->roles()->sync(2);

        // $success['token'] =  $user->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;
        $success['name'] =  $user->name;   

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email'             => 'required|email',
            'password'          => 'required|min:8'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $remember_me = !is_null($request->remember) ? true : false;
        $credentialsOnly = [
            'email'    => $request->email,
            'password' => $request->password,
        ]; 


        if(Auth::attempt($credentialsOnly, $remember_me)){
            $user = User::find(Auth::id());
            if(is_null($user->email_verified_at)){
                // $user->NotificationSendToVerifyEmail();
                return $this->sendError('unverified.', ['error_message'=>'Your account is not verified', 'verify_mail_send' => true]);
            }

            $accessToken = $user->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;
            $success = [
                'access_token'      => $accessToken,
                'remember_me_token' => $user->remember_token,
                'user'              => $user,
            ];

            return $this->sendResponse($success, 'You have logged in successfully!', $accessToken);
        } else{
            return $this->sendError('Unauthorised.', ['error_message'=>'These credentials do not match our records.']);
        }
    }

    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        
        $token = Str::random(64);
        $email_id = $request->email;
        $user = User::where('email', $email_id)->first();

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

        return $this->sendResponse(['send_email' => true], __('passwords.sent'));
    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'password'                  => 'required|min:8|confirmed',
            'password_confirmation'     => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $token = $request->token;
        $email = decrypt($request->hash);

        $updatePassword = DB::table('password_resets')->where(['email' => $email,'token' => $token])->first();

        if(!$updatePassword){
            return $this->sendError('Invalid Token', ['error_message' => trans('passwords.token')]);
        }else{
            $user = User::where('email', $email)
            ->update(['password' => bcrypt($request->password)]);

            DB::table('password_resets')->where(['email'=> $email])->delete();

            // Set Flash Message
            return $this->sendResponse(['password_reset' => true], __('passwords.reset'));
        }
    }

    public function verifyEmail($id, $hash){
        $user = User::find($id);
        
        if(!is_null($user->email_verified_at)){
            return $this->sendResponse(['already_verified' => true], 'Email is aleready verifed.');
        }
        if ($user && $hash === sha1($user->email)) {
            $user->update(['email_verified_at' => date('Y-m-d H:i:s')]);

            // Email verification success
            return $this->sendResponse(['mail_verify' => true], 'User login successfully.');
        }

        // Email verification failed
        return $this->sendError('verification Failed', ['error'=>'Mail verification failed']);
    }
}
