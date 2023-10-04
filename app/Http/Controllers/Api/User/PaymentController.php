<?php
namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Event;
use Stripe\Customer;
use App\Models\Plan;
use App\Models\Addon;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Subscription;
use App\Models\UserToken;
use Illuminate\Support\Facades\DB; 
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
            'type' => 'required',
        ]);

        try {
            // Set your Stripe secret key
            Stripe::setApiKey(config('app.stripe_secret_key'));

            $planId = $request->plan; // Replace with the actual Price ID

            $authUser = auth()->user();

            //Start To Set Token
            $token = Str::random(32);
            $userToken = UserToken::where('user_id',$authUser->id)->first();
            if($userToken){
                $userToken->plan_stripe_id = $planId;
                $userToken->token = $token;
                $userToken->type = 'checkout_token';
                $userToken->save();
            }else{
                $userToken = UserToken::create([
                    'user_id' => $authUser->id,
                    'plan_stripe_id'=>$planId,
                    'token'   => $token,
                    'type'    => 'checkout_token',
                ]);
            }
            //End To Set Token

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
                'plan'=>$planId,
            ];

            if($request->type == 'addon'){
                $sessionData = [
                    'payment_method_types' => ['card'],

                    // 'price_data' => [
                    //     # The currency parameter determines which
                    //     # payment methods are used in the Checkout Session.
                    //     'currency' => 'eur',
                    //     'product_data' => [
                    //       'name' => 'T-shirt',
                    //     ],
                    //     'unit_amount' => 2000,
                    //   ],
                    //   'quantity' => 1,

                    'line_items' => [
                        [
                            'price' => $planId,
                            'quantity' => 1, // Adjust as needed
                        ],
                    ],
                    'mode' => 'payment',
                    'success_url' => env('FRONTEND_URL').'completion/'.$token, // Replace with the actual success URL
                    'cancel_url' => env('FRONTEND_URL').'cancel',   // Replace with the actual cancel URL 
                    'metadata'=>$metadata,
                ];
            }else{
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
            }
            

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
        $requestToken = $request->token;
        $authUser = auth()->user();
        $userToken = UserToken::where('user_id',$authUser->id)->where('token', $requestToken)->first();
        if($userToken){
            // The request is from the same session.
            $plan = Plan::where('plan_stripe_id',$userToken->plan_stripe_id)->first();
            $addonPlan = Addon::where('price_stripe_id',$userToken->plan_stripe_id)->first();
            
            if($plan){
                $authUser->credit_limit = $plan->credits ?? 0;
            }else if($addonPlan){
                $authUser->credit_limit = (int)$authUser->credit_limit + (int)$addonPlan->credit;
            }

            $authUser->level_type = 2;
            $authUser->save();

            $userToken->token = null;
            $userToken->plan_stripe_id = null;
            $userToken->save();

            return response()->json(['status'=>true,'credit_limit'=>$authUser->credit_limit], 200);
        } else {
            // The request is not from the same session.
            return response()->json(['status'=>false], 404);
        }
    }


   public function handleStripeWebhook(Request $request){

    Log::info('Start stripe webhook');

        Stripe::setApiKey(config('app.stripe_secret_key'));
        $payload = $request->getContent();
        $stripeSignatureHeader = $request->header('Stripe-Signature');

        $endpointSecret = env('STRIPE_WEBHOOK_SECRET_KEY'); // Replace with the actual signing secret

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $stripeSignatureHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::info('Invalid payload!');
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::info('Invalid signature!');
            $data = [
                'error_message'=>$e->getMessage().'->'.$e->getLine()
            ];
            return response()->json(['error' => 'Invalid signature','data'=>$data], 400);
        }

        try {
            // Handle the event based on its type
            switch ($event->type) {
                case 'invoice.payment_succeeded':
                    
                    Log::info('Payment successfully!');

                    // Handle successful payment event
                    $paymentIntent = $event->data->object;


                    $customer_stripe_id = $paymentIntent->customer;

                    $customerId = User::where('stripe_customer_id',$customer_stripe_id)->value('id');
                
                    $isAddon= false;
                    $planId = Plan::where('plan_stripe_id',$paymentIntent->lines->data[0]->plan->id)->value('id');
                    if(!$planId){
                        $planId = Addon::where('product_stripe_id',$paymentIntent->lines->data[0]->plan->id)->value('id');
                        $isAddon = true;
                    }

                    $transaction = Transaction::where('user_id',$customerId)->where('payment_intent_id',$paymentIntent->id)->exists(); 
                    if(!$transaction){
                        // Save data to transactions table
                        Transaction::create([
                            'user_id' => $customerId,
                            'plan_id' => $planId, 
                            'is_addon' => $isAddon, 
                            'payment_intent_id' => $paymentIntent->payment_intent,
                            'amount' => (float)$paymentIntent->lines->data[0]->amount/100,
                            'currency' => $paymentIntent->lines->data[0]->currency,
                            'payment_method' => null,
                            'payment_type'   => 'credit',
                            'status' => 'success',
                            'payment_json' => json_encode($event),
                        ]);
                    }
                
                break;
                
                case 'checkout.session.completed':
                    Log::info('Payment successfully of additional plan!');
    
                    // Handle successful payment event
                    $paymentIntent = $event->data->object;

                    $customer_stripe_id = $paymentIntent->customer;
    
                    if(isset($paymentIntent->metadata->plan)){
                        $customerId = User::where('stripe_customer_id',$customer_stripe_id)->value('id');
                         
                        $planId = Addon::where('price_stripe_id',$paymentIntent->metadata->plan)->value('id');
                         
                        $transaction = Transaction::where('user_id',$customerId)->where('payment_intent_id',$paymentIntent->id)->exists(); 
                        if(!$transaction){
                            // Save data to transactions table
                            Transaction::create([
                                'user_id' => $customerId,
                                'plan_id' => $planId, 
                                'is_addon' => true, 
                                'payment_intent_id' => $paymentIntent->payment_intent,
                                'amount' => (float)$paymentIntent->amount_total/100,
                                'currency' => $paymentIntent->currency,
                                'payment_method' => $paymentIntent->payment_method_types[0],
                                'payment_type'   => 'credit',
                                'status' => 'success',
                                'payment_json' => json_encode($event),
                            ]);
                        }
                    }
                break;
                
                case 'payment_intent.payment_failed':
                    Log::info('Payment Failed!');
                    // Handle subscription update event
                    $paymentIntent = $event->data->object;
                    
                    $customer_stripe_id = $paymentIntent->customer;

                    $customer = User::where('stripe_customer_id',$customer_stripe_id)->first();

                    $transaction = Transaction::where('user_id',$customer->id)->where('payment_intent_id',$paymentIntent->id)->exists(); 
                    if(!$transaction){
                        // Save data to transactions table
                        Transaction::create([
                            'user_id' => $customerId,
                            'payment_intent_id' => $paymentIntent->id,
                            'amount' => (float)$paymentIntent->amount/100,
                            'currency' => $paymentIntent->currency,
                            'payment_method' => $paymentIntent->payment_method_types[0],
                            'payment_type'   => 'credit',
                            'status' => 'failed',
                            'payment_json' => json_encode($paymentIntent),
                        ]);

                        $customer->level_type = 1;
                        $customer->save();
                    }
                break;
                default:
                 Log::info('Invalid Event fired!');
               
            }
        } catch (\Exception $e) {
           
            // dd($e->getMessage().'->'.$e->getLine());
            // return response()->json(['error' => $e->getMessage().'->'.$e->getLine()], 400);
            return response()->json(['error' => 'Something went wrong!'], 400);
        }

        Log::info('End stripe webhook');

        return response()->json(['success' => true]);

   }


   public function paymentHistory(){
    
    try {
        $userId = auth()->user()->id;
        $transactions = Transaction::select('id','user_id','plan_id','amount','currency','is_addon','status','payment_type','created_at')->where('user_id',$userId)->orderBy('created_at','desc')->paginate(10);

        foreach($transactions as $transaction){
            $transaction->user_name = $transaction->user->name ?? '';

            if($transaction->is_addon == 0){
                $transaction->plan_name = $transaction->plan->title ?? '';
            }

            if($transaction->is_addon == 1){
                $transaction->plan_name = $transaction->addonPlan->title ?? '';
            }

        }
        return response()->json(['status'=>true,'result'=>$transactions], 200);
    } catch (\Exception $e) {
        // dd($e->getMessage().'->'.$e->getLine());
        return response()->json(['error' => 'Something went wrong!'], 400);
    }

   }


}
?>