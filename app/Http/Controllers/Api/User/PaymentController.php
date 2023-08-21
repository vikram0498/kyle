<?php
namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Stripe\Stripe;
use Stripe\Event;
use Stripe\Customer;
use Stripe\Subscription;
use App\Models\Transaction;

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

        $this->stripeSecret = Stripe::setApiKey(config('app.stripe_secret_key')); // test stripe pub key
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

   public function createSubscription(Request $request){
        $request->validate([
            'plan' => 'required'
        ]);
        try {
            $authUser = auth()->user();

            $customer = Customer::create([
                'email' => $authUser->email,
                // 'source' => $request->payment_method, // Payment method ID or token
            ]);

            $subscription = Subscription::create([
                'customer' => $authUser->id,
                'plan'     => $request->input('plan_id'),
            ]);

            return response()->json(['message' => 'Subscription created successfully', 'subscription_id' => $subscription->id]);
        } catch (\Exception $e) {
            dd($e->getMessage().'->'.$e->getLine());
            return response()->json(['error' => $e->getMessage()], 500);
        }
   }

   public function handleStripeWebhook(Request $request){
        // Verify the webhook signature
        $payload = @file_get_contents('php://input');
        $event = Event::constructFrom(json_decode($payload, true));

        // Handle different types of webhook events
        if ($event->type === 'invoice.payment_succeeded') {
            // Handle successful payment
        } elseif ($event->type === 'invoice.payment_failed') {
            // Handle failed payment
        }

        return response()->json(['status' => 'success']);
   }

}
?>