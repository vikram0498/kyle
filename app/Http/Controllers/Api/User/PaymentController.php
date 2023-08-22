<?php
namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Event;
use Stripe\Customer;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\Subscription;

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
        // try {
            
            $createPaymentIntent = true;
            $authUser = auth()->user();
            $plan = Plan::where('plan_token',$request->plan)->first();
            if($plan){

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

                // Retrieve the last payment intent for the customer
                $intents = PaymentIntent::all([
                    'customer' =>  $customer->id,
                    'limit' => 1,
                ]);

                if(!empty($intents->data)){
                    if($intents->data[0]->status =='incomplete'){
                        $createPaymentIntent = false;

                        $lastPaymentIntent = $intents->data[0];
    
                        // update subscription for the customer.
                        $subscription = \Stripe\Subscription::update([
                            'customer' => $customer->id,
                            'plan' => $plan->plan_token,
                        ]);
    
                        $paymentIntent = PaymentIntent::update($lastPaymentIntent->id, [
                            'amount' => (float)$plan->price * 100,
                            'currency' => config('constants.currency'),
                            'customer' => $customer->id,
                            'subscription' => $subscription->id,
                            'automatic_payment_methods' => ['enabled' => 'true'],
                        ]);
                    }
                }
                
                if($createPaymentIntent){
                    // // Create a new subscription for the customer.
                    // $subscription = \Stripe\Subscription::create([
                    //     'customer' => $customer->id,
                    //     'plan' => $plan->plan_token,
                    //     'on_subscription' =>'off',
                    // ]);

                    // Subscription::create([
                    //     'plan_id'                => $plan->id,
                    //     'stripe_customer_id'     => $customer->id,
                    //     'stripe_plan_id'         => $plan->plan_token,
                    //     'stripe_subscription_id' => $subscription->id,
                    //     'start_date'             => $subscription->start_date,
                    //     'end_date'               => $subscription->end_date,
                    //     'subscription_json'      => json_encode($subscription),
                    // ]);


                    $paymentIntent = PaymentIntent::create(
                        [
                            'amount' => (float)$plan->price * 100,
                            'currency' => config('constants.currency'),
                            'customer' => $customer->id,
                            // 'subscription' => $subscription->id,
                            'automatic_payment_methods' => ['enabled' => 'true'],
                        ]
                    );
                }
    
                return response()->json(['status'=>true,'client_secret'=>$paymentIntent->client_secret,'message'=>'Success'], 200);
            }else{
                $responseData = [
                    'status'        => false,
                    'error'         => 'Invalid Plan!',
                ];
                return response()->json($responseData, 500);
            }
        // }catch(\Exception $e){
        //     dd($e->getMessage().'->'.$e->getLine());
        //    $responseData = [
        //        'status'        => false,
        //        'error'         => trans('messages.error_message'),
        //    ];
        //    return response()->json($responseData, 400);
        // }
   }

   public function createSubscription(Request $request){
        $request->validate([
            'payment_intent' =>'required',
            'payment_intent_client_secret' =>'required',
        ]);
        try {
            if($request->redirect_status == 'succeeded'){
                $authUser = auth()->user();
               
                $authUser->level_type = 2;
                $authUser->save();

               $paymentIntentObject =  Stripe\PaymentIntent::retrieve($request->payment_intent);

                Transaction::create([
                    'user_id'  => $authUser->id,
                    'amount'   => '', 
                    'currency' => config('constants.currency'),
                    'status'   => $request->redirect_status,
                    'payment_method'   => null,
                    'payment_json'   => null,
                ]);
              
                return response()->json(['status'=>true,'message' => 'Success']);
            }
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

        // // Handle the event
        // switch ($event->type) {
        //     case 'payment_intent.succeeded':
        //         $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
        //         // Then define and call a method to handle the successful payment intent.
        //         // handlePaymentIntentSucceeded($paymentIntent);
        //         break;
        //     case 'payment_method.attached':
        //         $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
        //         // Then define and call a method to handle the successful attachment of a PaymentMethod.
        //         // handlePaymentMethodAttached($paymentMethod);
        //         break;
        //     // ... handle other event types
        //     default:
        //         echo 'Received unknown event type ' . $event->type;
        // }

        return response()->json(['status' => 'success']);
   }

}
?>