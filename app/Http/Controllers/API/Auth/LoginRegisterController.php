<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginRegisterController extends BaseController
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required',
            'last_name'         => 'required',
            'email'             => 'required|email|unique:users,email',
            'phone'             => 'required|numeric|digits:10|unique:users,phone',
            'address'           => 'required',
            'company_name'      => 'required',
            'password'          => 'required',
            'confirm_password'  => 'required|same:password',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['name'] = $input['first_name'].' '.$input['last_name'];
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        //Verification mail sent
        $user->sendEmailVerificationNotification();

        $user->roles()->sync(2);

        $success['token'] =  $user->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;
        $success['name'] =  $user->name;   

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            if(is_null($user->email_verified_at)){
                return $this->sendError('unverified.', ['error'=>'Your account is not verified']);
            }
            $success['token'] =  $user->createToken(env('APP_NAME', 'Kyle'))->plainTextToken;
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        } else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
}
