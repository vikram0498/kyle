<?php

namespace App\Http\Controllers\Api\User;

use Stripe\Stripe;
use Stripe\Event;
use Stripe\Customer;
use Stripe\Checkout\Session as StripeSession;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;

class BuyerVerificationController extends Controller
{
    public function index(Request $request){
    
        switch ($request->step) {
            case 1:
                return $this->phoneVerification($request);
            break;
            case 2:
                return $this->proofOfFundsVerification($request);
            break;
            case 3:
                return $this->driverLicenseVerification($request);
            break;
            case 4:
                return $this->LLCVerification($request);
            break;
            case 5:
                return $this->applicationProcess($request);
            break;
            default:
               //Return Error Response
                $responseData = [
                    'status'        => false,
                    'error'         => trans('messages.error_message'),
                ];
                return response()->json($responseData, 400);
        }

    }

    private function phoneVerification($request){
        $userId = auth()->user()->id;

        $combinedOTP = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4;

        $rules['phone'] = ['required', 'numeric','not_in:-','unique:users,phone,'.$userId.',id,deleted_at,NULL'];
        $rules['otp'] = ['required','numeric','digits:4','not_in:-'];

        // $rules['otp1'] = ['required','numeric','digits:1','not_in:-'];
        // $rules['otp2'] = ['required','numeric','digits:1','not_in:-'];
        // $rules['otp3'] = ['required','numeric','digits:1','not_in:-'];
        // $rules['otp4'] = ['required','numeric','digits:1','not_in:-'];
       
        $request->validate($rules,[
            'otp.required' => 'Please enter the OTP.',
            'otp.digits' => 'The OTP must be exactly 4 digits.',
        ],[]);

        DB::beginTransaction();
        try {
            $otpNumber = (int)$request->otp1.$request->otp2.$request->otp3.$request->otp4;
            
            $otpVerify = User::where('id',$userId)->where('otp',$otpNumber)->first();
            if($otpVerify){
                $otpVerify->otp = null;
                $otpVerify->phone = $request->phone;
                $otpVerify->phone_verified_at = date('Y-m-d H:i:s');
                $otpVerify->save();
                $otpVerify->buyerVerification()->update(['is_phone_verification'=>1]);

                DB::commit();
                //Return Success Response
                $responseData = [
                    'status'        => true,
                    'current_step'  => $request->step,
                    'message'       => trans('messages.auth.verification.phone_verify_success'),
                ];
                return response()->json($responseData, 200);
            }else{
                //Return Error Response
                $responseData = [
                    'status'        => false,
                    'error'         => trans('messages.auth.verification.invalid_otp'),
                ];
                return response()->json($responseData, 400);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            //  dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 400);
        }
    }

    private function driverLicenseVerification($request){
       
        /**
         * Rules
         * image|mimes:jpeg,png,gif|max:2048|dimensions:min_width=800,min_height=600
         */
        $rules['driver_license_front_image'] = ['required','image','mimes:jpeg,jpg,png','max:2048'];
        $rules['driver_license_back_image']  = ['required','image','mimes:jpeg,jpg,png','max:2048'];

        $customMessage = [];

        $attributNames = [
            'driver_license_front_image' => 'front id photo',
            'driver_license_back_image' => 'back id photo',
        ];

        $request->validate($rules,$customMessage,$attributNames);

        DB::beginTransaction();
        try {
            $userId = auth()->user()->id;
            $user = User::where('id',$userId)->first();
            if($user){

                // Start front image upload
                $uploadFrontId = null;
                if ($request->driver_license_front_image) {
                    if($user->driverLicenseFrontImage){
                        $uploadFrontId = $user->driverLicenseFrontImage->id;
                        uploadImage($user, $request->driver_license_front_image, 'buyer/verification/',"driver-license-front", 'original', 'update', $uploadFrontId);
                    }else{
                        uploadImage($user, $request->driver_license_front_image, 'buyer/verification/',"driver-license-front", 'original', 'save', $uploadFrontId);
                    }
                }

                // Start back image upload
                $uploadBackId = null;
                if ($request->driver_license_back_image) {
                    if($user->driverLicenseBackImage){
                        $uploadBackId = $user->driverLicenseBackImage->id;
                        uploadImage($user, $request->driver_license_back_image, 'buyer/verification/',"driver-license-back", 'original', 'update', $uploadBackId);
                    }else{
                        uploadImage($user, $request->driver_license_back_image, 'buyer/verification/',"driver-license-back", 'original', 'save', $uploadBackId);
                    }
                }

                $user->buyerVerification()->update(['is_driver_license'=> 1, 'driver_license_status' => 'pending']);

                DB::commit();

                //Return Success Response
                $responseData = [
                    'status'        => true,
                    'current_step'  => $request->step,
                    'message'       => trans('messages.auth.verification.driver_license_success'),
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
            ];
            return response()->json($responseData, 400);
        }
    }

    private function proofOfFundsVerification($request){
        /**
         * Rules
         * file|mimes:pdf|max:5120|
        */
        $rules['bank_statement_pdf']   = ['required','file','mimes:pdf'];
        $rules['other_proof_of_fund']  = ['required','string'];

        $customMessage = [];

        $attributNames = [
            'bank_statement_pdf' => 'bank statement',
        ];

        $request->validate($rules,$customMessage,$attributNames); 

        DB::beginTransaction();
        try {
            $userId = auth()->user()->id;
            $user = User::where('id',$userId)->first();
            if($user){

                // Start bank statement pdf upload
                $uploadId = null;
                if($request->bank_statement_pdf) {
                    if($user->bankStatementPdf){
                        $uploadId = $user->bankStatementPdf->id;
                        uploadImage($user, $request->bank_statement_pdf, 'buyer/verification/',"bank-statement-pdf", 'original', 'update', $uploadId);
                    }else{
                        uploadImage($user, $request->bank_statement_pdf, 'buyer/verification/',"bank-statement-pdf", 'original', 'save', $uploadId);
                    }
                }

                $user->buyerVerification()->update(['other_proof_of_fund'=>$request->other_proof_of_fund,'is_proof_of_funds'=>1, 'proof_of_funds_status' => 'pending']);

                DB::commit();

                //Return Success Response
                $responseData = [
                    'status'        => true,
                    'current_step'  => $request->step,
                    'message'       => trans('messages.auth.verification.proof_of_funds_success'),
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
            ];
            return response()->json($responseData, 400);
        }
    }

    private function LLCVerification($request){
        /**
         * Rules
         * image|mimes:jpeg,png,gif|max:2048|dimensions:min_width=800,min_height=600
        */
        $rules['llc_front_image'] = ['required','image','mimes:jpeg,jpg,png','max:2048'];
        $rules['llc_back_image']  = ['required','image','mimes:jpeg,jpg,png','max:2048'];

        $customMessage = [];

        $attributNames = [
            'llc_front_image' => 'front id photo',
            'llc_back_image'  => 'back id photo',
        ];

        $request->validate($rules,$customMessage,$attributNames);

        DB::beginTransaction();
        try {
            $userId = auth()->user()->id;
            $user = User::where('id',$userId)->first();
            if($user){

                // Start front image upload
                $uploadFrontId = null;
                if ($request->llc_front_image) {
                    if($user->llcFrontImage){
                        $uploadFrontId = $user->llcFrontImage->id;
                        uploadImage($user, $request->llc_front_image, 'buyer/verification/',"llc-front-image", 'original', 'update', $uploadFrontId);
                    }else{
                        uploadImage($user, $request->llc_front_image, 'buyer/verification/',"llc-front-image", 'original', 'save', $uploadFrontId);
                    }
                }

                // Start back image upload
                $uploadBackId = null;
                if ($request->llc_back_image) {
                    if($user->llcBackImage){
                        $uploadBackId = $user->llcBackImage->id;
                        uploadImage($user, $request->llc_back_image, 'buyer/verification/',"llc-back-image", 'original', 'update', $uploadBackId);
                    }else{
                        uploadImage($user, $request->llc_back_image, 'buyer/verification/',"llc-back-image", 'original', 'save', $uploadBackId);
                    }
                }

                $user->buyerVerification()->update(['is_llc_verification'=>1, 'llc_verification_status' => 'pending']);
 
                DB::commit();

                //Return Success Response
                $responseData = [
                    'status'        => true,
                    'current_step'  => $request->step,
                    'message'       => trans('messages.auth.verification.llc_verification_success'),
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
            ];
            return response()->json($responseData, 400);
        }
    }

    private function applicationProcess($request){
        DB::beginTransaction();
        try {
            $userId = auth()->user()->id;
            $authUser = User::where('id',$userId)->first();
            if($authUser){
                // Set your Stripe secret key
                Stripe::setApiKey(config('app.stripe_secret_key'));

                // Create or retrieve Stripe customer
                if (!$authUser->stripe_customer_id) {
                    $customer = Customer::create([
                        'name'  => $authUser->name,
                        'email' => $authUser->email,
                    ]);
                    $authUser->stripe_customer_id = $customer->id;
                    $authUser->save();
                } else {
                    $customer = Customer::retrieve($authUser->stripe_customer_id);
                }


                $metadata = [
                    'user_type' => 'buyer',
                    'product_type' => 'application_fee',
                    'description'  => 'Application Fee Payment',
                ];

                $sessionData = [
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'price' => config('constants.buyer_application_free_price_id'),
                            'quantity' => 1,
                        ],
                    ],
                    'mode' => 'payment',
                    'metadata' => $metadata,
                    'success_url' => env('FRONTEND_URL').'buyer-profile',
                    'cancel_url' => env('FRONTEND_URL').'profile-verification',
                ];

                
                // If customer ID is provided, set it in the session data
                if ($customer) {
                    $sessionData['customer'] = $customer->id;
                }

                // Create a Checkout Session
                $session = StripeSession::create($sessionData);

                DB::commit();

                return response()->json(['status'=>true,'current_step'  => $request->step,'session' => $session],200);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            //  dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => $e->getMessage().'->'.$e->getLine(),
            ];
            return response()->json($responseData, 400);
        }
    }

    public function getLastVerificationForm(){
        $lastStepForm = 0;
        $statusOfLastStep = 0;
        $user = auth()->user();

        if($user->buyerVerification->is_phone_verification){
            $lastStepForm = 1;
        }

    
        if($user->buyerVerification->is_proof_of_funds){
            $lastStepForm = 2;
            $statusOfLastStep = $user->buyerVerification->proof_of_funds_status;
        }

        if($user->buyerVerification->is_driver_license){
            $lastStepForm = 3;
            $statusOfLastStep = $user->buyerVerification->driver_license_status;
        }

        if($user->buyerVerification->is_llc_verification){
            $lastStepForm = 4;
            $statusOfLastStep = $user->buyerVerification->llc_verification_status;
        }

        if($user->buyerVerification->is_application_process){
            $lastStepForm = 5;
        }

        if($lastStepForm == 3){
            $reasonType = config('constants.pof_reason_type');
        }else{
            $reasonType = config('constants.ids_reason_type');
        }

        // $reasonMess = 'Your verification has been failed. so the following reason has been given below:';
        $reasonMess = '';
        if($user->buyerVerification->reason_type == 'other'){
             $reasonMess = $user->buyerVerification->reason_content;
        }elseif(isset($reasonType[$user->buyerVerification->reason_type])){
            $reasonMess = $reasonType[$user->buyerVerification->reason_type];
        }
        
        //Return Success Response
        $responseData = [
            'status'                    => true,
            'lastStepForm'              => (int)$lastStepForm,

            'lastStepStatus'            => $statusOfLastStep,

            'reason_content'            => $reasonMess, 

            // 'driver_license_status'     => $user->buyerVerification->driver_license_status,
            // 'proof_of_funds_status'     => $user->buyerVerification->proof_of_funds_status,
            // 'llc_verification_status'   => $user->buyerVerification->llc_verification_status,
        ];
        return response()->json($responseData, 200);
    }
}