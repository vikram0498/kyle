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
use Illuminate\Support\Facades\DB; 
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(Request $request)
    {
        $this->default_currency = config('constants.default_currency');
        $this->currency = $request->currency;

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

    public function createCheckoutSession(Request $request)
    {
        $request->validate([
            'plan' =>'required',
        ]);

        try {
            // Set your Stripe secret key
            Stripe::setApiKey(config('app.stripe_secret_key'));

            $planId = $request->plan; // Replace with the actual Price ID

            $authUser = auth()->user();

            $token = Str::random(32);

            $request->session()->put('token', $token);

            // Create or retrieve Stripe customer
            if (!$authUser->stripe_customer_id) {
                $customer = Customer::create([
                    'name'  => $authUser->name,
                    'email' => $authUser->email,
                    // 'payment_method' => $paymentIntentObject['payment_method'],
                ]);
                $authUser->stripe_customer_id = $customer->id;
                $authUser->save();
            } else {
                $customer = Customer::retrieve($authUser->stripe_customer_id);
            }

            $sessionData = [
                'payment_method_types' => ['card'],
                'subscription_data' => [
                    'items' => [
                        ['plan' => $planId],
                    ],
                ],
                'mode' => 'subscription',
                'success_url' => env('FRONTEND_URL').'completion/'.$token, // Replace with the actual success URL
                'cancel_url' => env('FRONTEND_URL').'cancel',   // Replace with the actual cancel URL    
            ];

            // If customer ID is provided, set it in the session data
            if ($customer) {
                $sessionData['customer'] = $customer->id;
            }

            // Create a Checkout Session
            $session = StripeSession::create($sessionData);

            return response()->json(['session' => $session]);
        }catch (\Exception $e) {
            // dd($e->getMessage().'->'.$e->getLine());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function checkoutSuccess(Request $request)
    {
        $sessionToken = session()->get('token');
        $requestToken = $request->token;

        // Compare the two tokens.
        if ($sessionToken === $requestToken) {
            // The request is from the same session.
            session()->forget('token');
            return response()->json(['status'=>true], 200);
        } else {
            // The request is not from the same session.
            return response()->json(['status'=>false], 500);
        }
    }

   public function createPaymentIntent(Request $request){
        Stripe::setApiKey(config('app.stripe_secret_key'));
        $request->validate([
            'plan' => 'required'
        ]);

        try {
            $createPaymentIntent = true;
            $authUser = auth()->user();
            $plan = Plan::where('plan_stripe_id',$request->plan)->first();
            if($plan){

                $authUser = auth()->user();

                // // Create or retrieve Stripe customer
                // if (!$authUser->stripe_customer_id) {
                //     $customer = Customer::create([
                //         'name'  => $authUser->name,
                //         'email' => $authUser->email,
                //         // 'payment_method' => $paymentIntentObject['payment_method'],
                //     ]);
                //     $authUser->stripe_customer_id = $customer->id;
                //     $authUser->save();
                // } else {
                //     $customer = Customer::retrieve($authUser->stripe_customer_id);
                // }

                // if($authUser->stripe_customer_id){
                //    // Retrieve the last payment intent for the customer
                //     $intents = PaymentIntent::all([
                //         'customer' =>  $authUser->stripe_customer_id,
                //         'limit' => 1,
                //     ]);
                //     if(!empty($intents->data)){
                //         $lastPaymentIntent = $intents->data[0];
                //         if($lastPaymentIntent->status =='incomplete'){
                //             $createPaymentIntent = false;
                //             $paymentIntent = PaymentIntent::update($lastPaymentIntent->id, [
                //                 'amount' => (float)$plan->price * 100,
                //                 'currency' =>  $this->default_currency,
                //                 'customer' => $customer->id,
                //                 'automatic_payment_methods' => ['enabled' => 'true'],
                //                 'description' => 'Subscription to My Plan',
                //                 'metadata' => [
                //                   'plan_id' => $plan->plan_stripe_id,
                //                 ],
                //             ]);
                //         }
                //     }

                // }
                
                if($createPaymentIntent){
                    $paymentIntent = PaymentIntent::create([
                        'amount' =>(float)$plan->price * 100, // Amount in cents
                        'currency' =>  $this->default_currency,
                        // 'customer' => $customer->id,
                        'automatic_payment_methods' => ['enabled' => 'true'],
                        'description' => 'Subscription to My Plan',
                        'metadata' => [
                          'plan_id' => $plan->plan_stripe_id,
                        ],
                    ]);
                }

                $clientSecret = $paymentIntent ? $paymentIntent->client_secret : null;
                return response()->json(['status'=>true,'client_secret'=>$clientSecret,'message'=>'Success'], 200);
            }else{
                $responseData = [
                    'status'        => false,
                    'error'         => 'Invalid Plan!',
                ];
                return response()->json($responseData, 500);
            }
        }catch(\Exception $e){
            // dd($e->getMessage().'->'.$e->getLine());
           $responseData = [
               'status'        => false,
               'error'         => trans('messages.error_message'),
           ];
           return response()->json($responseData, 400);
        }
   }

   public function createSubscription(Request $request){
        $request->validate([
            'payment_intent' =>'required',
            'payment_intent_client_secret' =>'required',
        ]);
        DB::beginTransaction();
        try {
            Stripe::setApiKey(config('app.stripe_secret_key'));

            if($request->redirect_status == 'succeeded'){

               $paymentIntentObject =  $this->fetchPaymentIntent($request->payment_intent);

               $paymentIntentObject = json_decode($paymentIntentObject->content(),true)['payment_intent'];

               $authUser = auth()->user();
               $authUser->level_type = 2;
               $authUser->save();

                // Get the plan ID from the payment intent.
                $stripePlanId = $paymentIntentObject['metadata']['plan_id'];

                $retrievPlan = Plan::where('plan_stripe_id',$stripePlanId)->first();

                if( $retrievPlan ){
                    // $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentIntentObject['payment_method']);
                    
                    // $paymentMethod->attach([
                    //     'customer' => $authUser->stripe_customer_id,
                    // ]);

                    // Update the payment method.
                    $customer = Customer::retrieve($authUser->stripe_customer_id);
                    // // Update the default payment method
                    // $customer['invoice_settings']->default_payment_method = $paymentMethod->id;
                    // $customer->save();
                  
                    // dd($customer);
                    
                    // $subscription = \Stripe\Subscription::create([
                    //     'customer' => $authUser->stripe_customer_id,
                    //     'plan' => $retrievPlan->plan_stripe_id,
                    // ]);

                    // Subscription::create([
                    //     'plan_id'                => $retrievPlan->id,
                    //     'stripe_customer_id'     => $authUser->stripe_customer_id,
                    //     'stripe_plan_id'         => $retrievPlan->plan_stripe_id,
                    //     'stripe_subscription_id' => $subscription->id,
                    //     'start_date'             => $subscription->start_date,
                    //     'end_date'               => $subscription->end_date,
                    //     'subscription_json'      => json_encode($subscription),
                    // ]);
                }
                
                // Transaction::create([
                //     'user_id'  => $authUser->id,
                //     'amount'   => (float)$paymentIntentObject->amount/100, 
                //     'currency' => config('constants.currency'),
                //     'status'   => $request->redirect_status,
                //     'payment_method'   => $paymentIntentObject->payment_method,
                //     'payment_json'   => json_encode($paymentIntentObject),
                // ]);
              
                DB::commit();
                return response()->json(['status'=>true,'message' => 'Success']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());
            return response()->json(['error' => $e->getMessage()], 500);
        }
   }

   public function fetchPaymentIntent($paymentIntentId){
    
    $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

    return response()->json(['status'=>true,'payment_intent'=>$paymentIntent,'message'=>'Success'], 200);

   }

   public function handleStripeWebhook(Request $request){

        Stripe::setApiKey(config('app.stripe_secret_key'));
        $payload = $request->getContent();
        $stripeSignatureHeader = $request->header('Stripe-Signature');

        $endpointSecret = env('STRIPE_WEBHOOK_SECRET_KEY'); // Replace with the actual signing secret

        try {
            $event = \Stripe\Webhook;Webhook::constructEvent(
                $payload, $stripeSignatureHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event based on its type
        switch ($event->type) {
            case 'payment_intent.succeeded':
                // Handle successful payment event
                $paymentIntent = $event->data->object;
                 // Save data to transactions table
                 Transaction::create([
                    'user_id' => auth()->user()->id,
                    'status' => 'payment_intent.succeeded',
                    'payment_json' => json_encode($paymentIntent),
                ]);
                break;
            case 'payment_intent.payment_failed':
                // Handle subscription update event
                $paymentIntent = $event->data->object;
                 // Save data to transactions table
                 Transaction::create([
                    'user_id' => auth()->user()->id,
                    'status' => 'payment_intent.payment_failed',
                    'payment_json' => json_encode($paymentIntent),
                ]);
                break;
            // Add more cases for other event types
        }

        return response()->json(['success' => true]);
       
   }

}
?>