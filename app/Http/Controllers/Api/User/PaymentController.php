<?php
namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Stripe\Stripe;
use Stripe\Event;
use Stripe\Customer;
use Stripe\Subscription;
use App\Models\Plan;
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
        $request->validate([
            'plan' => 'required'
        ]);
        try {
            
            $authUser = auth()->user();
            $plan = Plan::where('plan_token',$request->plan)->first();
            if($plan){

                // Create or retrieve Stripe customer
                if (!$authUser->stripe_customer_id) {
                    $customer = Customer::create([
                        'email' => $authUser->email,
                        // Add any additional customer data here
                    ]);
                    $authUser->stripe_customer_id = $customer->id;
                    $authUser->save();

                } else {
                    $customer = Customer::retrieve($authUser->stripe_customer_id);
                }

                $paymentIntent = \Stripe\PaymentIntent::create(
                    [
                        'amount' => (float)$plan->price * 100,
                        'currency' => 'usd',
                        'customer' => $customer->id,
                        'automatic_payment_methods' => ['enabled' => 'true'],
                    ]
                );
    
                return response()->json(['status'=>true,'message'=>'Success'], 200);
            }else{
                $responseData = [
                    'status'        => false,
                    'error'         => 'Invalid Plan!',
                ];
                return response()->json($responseData, 500);
            }
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

           // Subscribe the user to the selected plan
            $subscription = Subscription::create([
                'customer' => $authUser->stripe_customer_id ,
                'items' => [['plan' => $request->input('plan')]],
                // Add any additional subscription data here
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