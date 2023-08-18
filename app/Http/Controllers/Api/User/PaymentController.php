<?php
namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Stripe;

class PaymentController extends Controller
{
    public function __construct(Request $request)
    {
        $this->default_currency = config('constants.default_currency');
        $this->currency = $request->currency;

        //    if($this->currency == 'CNY'){
        //     $this->payment_method_types = ['card'];
        //    }else{
        //       $this->payment_method_types = ['card'];
        //    }

        $this->stripeSecret = \Stripe\Stripe::setApiKey(config('app.stripe_secret_key')); // test stripe pub key
    }

    public function config(){
         try {
         	
         	$responseData = [
                'status'            => true,
                'key'               => config('app.stripe_publishable_key'),
            ];

         	return response()->json($responseData, 200);
         }catch(\Exception $e){
			$responseData = [
			    'status'        => false,
			    'error'         => trans('messages.error_message'),
			];
			return response()->json($responseData, 400);
         }
    }

    public function createPaymentIntent(Request $request){
        try {

            $paymentIntent = \Stripe\PaymentIntent::create(
                [
                    'amount' => 3000,
                    'currency' => 'INR',
                    'automatic_payment_methods' => ['enabled' => 'true'],
                ]
            );

            return response()->json($paymentIntent->client_secret, 200);
        }catch(\Exception $e){
           $responseData = [
               'status'        => false,
               'error'         => trans('messages.error_message'),
           ];
           return response()->json($responseData, 400);
        }
   }

}
?>