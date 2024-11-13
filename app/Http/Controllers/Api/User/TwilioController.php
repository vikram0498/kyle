<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Services\TwilioService;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;


class TwilioController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function sendOTP(Request $request)
    {
        $userId = auth()->user()->id;
        $rules['country_code'] = ['required', 'numeric'];

        // $rules['phone'] = ['required', 'numeric','digits:10','not_in:-','unique:users,phone,'. $userId.',id,deleted_at,NULL'];

        $rules['phone'] = [
            'required', 'numeric','digits:10','not_in:-',
            Rule::unique('users')->where(function ($query) use ($request, $userId) {
                return $query->where('country_code', $request->country_code);
            })->ignore($userId, 'id')
        ];

        $request->validate($rules);

        try {
            DB::beginTransaction();
           
            $otpNumber = generateOTP();
          
            $isOtpSend = User::where('id',$userId)->update(['country_code'=>$request->country_code,'phone'=>$request->phone,'otp'=>$otpNumber]);
            if($isOtpSend){

                $toPhoneNumber = '+'.$request->country_code.$request->phone;
                $message = trans('messages.otp_sms_content',['otpNumber'=>$otpNumber]);
                $this->twilio->send_SMS($toPhoneNumber, $message);

                DB::commit();
                //Return Success Response
                $responseData = [
                    'status'        => true,
                    'message'       => trans('messages.auth.verification.otp_send_success'),
                ];
                return response()->json($responseData, 200);
            }
        }catch (\Exception $e) {
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
}
