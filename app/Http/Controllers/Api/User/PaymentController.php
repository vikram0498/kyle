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
use App\Models\Buyer;
use App\Models\BuyerPlan;
use App\Models\BuyerTransaction;
use Stripe\Subscription as StripeSubscription;
use App\Models\Subscription;
use App\Models\UserToken;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct(Request $request)
    {
        $this->default_currency = config('constants.default_currency');
        $this->currency = $request->currency;
        $this->stripeSecret = Stripe::setApiKey(config('app.stripe_secret_key')); // test stripe pub key
    }

    public function config()
    {
        try {
            $responseData = [
                'status'            => true,
                'key'               => config('app.stripe_publishable_key'),
            ];

            return response()->json($responseData, 200);
        } catch (\Exception $e) {
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
            'plan' => 'required',
            'type' => 'required',
        ]);

        try {
            // Set your Stripe secret key
            Stripe::setApiKey(config('app.stripe_secret_key'));

            $planId = $request->plan; // Replace with the actual Price ID

            $authUser = auth()->user();

            //Start To Set Token
            $token = Str::random(32);
            $userToken = UserToken::where('user_id', $authUser->id)->first();
            if ($userToken) {
                $userToken->update([
                    'plan_stripe_id'=>$planId,
                    'token'=>$token,
                    'type'=>'checkout_token',
                ]);
            } else {
                $userToken = UserToken::create([
                    'user_id' => $authUser->id,
                    'plan_stripe_id' => $planId,
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
                'plan' => $planId,
            ];

            if ($request->type == 'addon') {
                $metadata['user_type'] = 'seller';
                $sessionData = [
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'price' => $planId,
                            'quantity' => 1,
                        ],
                    ],
                    'mode' => 'payment',
                    'success_url' => env('FRONTEND_URL') . 'completion/' . $token, 
                    'cancel_url' => env('FRONTEND_URL') . 'cancel',    
                    'metadata' => $metadata,
                ];
            } else  if ($request->type == 'boost_your_profile') {
                $active_subscription = $authUser->subscription()->where('status','active')->first() ? $authUser->subscription()->where('status','active')->first()->stripe_subscription_id : '';

                $metadata = [
                    'user_type' => 'buyer',
                    'product_type' => 'boost-plan',
                    'description'  => 'Boost Plan',
                    'active_subscription' => $active_subscription,
                ];
                $sessionData = [
                    'payment_method_types' => ['card'],
                    'subscription_data' => [
                        'items' => [
                            ['plan' => $planId],
                        ],
                        'metadata' => $metadata,
                    ],
                    'mode' => 'subscription',
                    'success_url' => env('FRONTEND_URL') . 'buyer-profile/' . $token,
                    'cancel_url' => env('FRONTEND_URL') . 'cancel',  
                ];
            } else {
                $metadata['user_type'] = 'seller';
                $sessionData = [
                    'payment_method_types' => ['card'],
                    'subscription_data' => [
                        'items' => [
                            ['plan' => $planId],
                        ],
                        'metadata' => $metadata,
                    ],
                    'mode' => 'subscription',
                    'success_url' => env('FRONTEND_URL') . 'completion/' . $token, 
                    'cancel_url' => env('FRONTEND_URL') . 'cancel',  
                ];
            }

            if ($customer) {
                $sessionData['customer'] = $customer->id;
            }

            // Create a Checkout Sessions
            $session = StripeSession::create($sessionData);

            return response()->json(['session' => $session]);
        } catch (\Exception $e) {
            // dd($e->getMessage().'->'.$e->getLine());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function checkoutSuccess(Request $request)
    {

        $requestToken = $request->token;
        $authUser = auth()->user();

        $userToken = UserToken::where('user_id', $authUser->id)->where('token', $requestToken)->first();

        if ($userToken) {
            // The request is from the same session.
            if ($authUser->is_buyer) {
                $buyerPlan = BuyerPlan::where('plan_stripe_id', $userToken->plan_stripe_id)->first();

                if($buyerPlan){
                $updateBuyerPlan = Buyer::where('buyer_user_id', $authUser->id)->update(['plan_id' => $buyerPlan->id, 'is_plan_auto_renew' =>1]);
                    $response = [
                        'status' => true,
                        'message' => 'Your payment is successfully completed.'
                    ];
                }else{
                    $response = [
                        'status' => true,
                        'message' => 'Invalid buyer plan.'
                    ];
                }
            } else {
                                        
                $plan = Plan::where('plan_stripe_id', $userToken->plan_stripe_id)->first();
                $addonPlan = Addon::where('price_stripe_id', $userToken->plan_stripe_id)->first();
                if ($plan) {
                    $authUser->credit_limit = $plan->credits ?? 0;
                } else if ($addonPlan) {
                    $authUser->credit_limit = (int)$authUser->credit_limit + (int)$addonPlan->credit;
                }
                $authUser->level_type = 2;
                $authUser->save();

                $response = ['status' => true, 'credit_limit' => $authUser->credit_limit];
            }
          
            $userToken->token = null;
            $userToken->plan_stripe_id = null;
            $userToken->save();
               
            return response()->json($response, 200);
        } else {
            // The request is not from the same session.
            return response()->json(['status' => false], 404);
        }
    }


    public function handleStripeWebhook(Request $request)
    {
        Log::info('Start stripe webhook');

        Stripe::setApiKey(config('app.stripe_secret_key'));
        $payload = $request->getContent();
        $stripeSignatureHeader = $request->header('Stripe-Signature');

        $endpointSecret = env('STRIPE_WEBHOOK_SECRET_KEY'); // Replace with the actual signing secret

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $stripeSignatureHeader,
                $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::info('Invalid payload!');
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::info('Invalid signature!');
            $data = [
                'error_message' => $e->getMessage() . '->' . $e->getLine()
            ];
            return response()->json(['error' => 'Invalid signature', 'data' => $data], 400);
        }

        try {
            // Handle the event based on its type
            switch ($event->type) {
                case 'invoice.payment_succeeded':

                    Log::info('Payment successfully!');

                    // Handle successful payment event
                    $paymentIntent = $event->data->object;

                    $customer_stripe_id = $paymentIntent->customer;

                    $metaData = $paymentIntent->subscription_details->metadata ?? null;
                    
                    if ($metaData && $metaData->user_type) {

                        if($metaData->user_type == 'seller'){
                            $customerId = User::where('stripe_customer_id', $customer_stripe_id)->value('id');

                            $isAddon = false;
                            $planId = Plan::where('plan_stripe_id', $paymentIntent->lines->data[0]->plan->id)->value('id');
                            if (!$planId) {
                                $planId = Addon::where('product_stripe_id', $paymentIntent->lines->data[0]->plan->id)->value('id');
                                $isAddon = true;
                            }
        
                            $transaction = Transaction::where('user_id', $customerId)->where('payment_intent_id', $paymentIntent->payment_intent)->where('status','success')->exists();
                            if (!$transaction) {
                                if (!is_null($customerId) && !is_null($planId)) {
                                    // Save data to transactions table
                                    Transaction::create([
                                        'user_id' => $customerId,
                                        'plan_id' => $planId,
                                        'is_addon' => $isAddon,
                                        'payment_intent_id' => $paymentIntent->payment_intent,
                                        'amount' => (float)$paymentIntent->lines->data[0]->amount / 100,
                                        'currency' => $paymentIntent->lines->data[0]->currency,
                                        'payment_method' => null,
                                        'payment_type'   => 'credit',
                                        'status' => 'success',
                                        'payment_json' => json_encode($event),
                                    ]);
                                }
                            }
                            
                        }else if($metaData->product_type == 'boost-plan' && $metaData->user_type == 'buyer'){
                            $authUser = User::where('stripe_customer_id', $customer_stripe_id)->first();

                            $buyerTransaction = BuyerTransaction::where('user_id', $authUser->id)->where('payment_intent_id', $paymentIntent->payment_intent)->where('status','success')->exists();

                            // return response()->json(['success' => true,'payment_intent'=>$buyerTransaction]);

                            if (!$buyerTransaction) {

                                if($metaData->active_subscription){
                                    $canceledSubscription = StripeSubscription::retrieve($metaData->active_subscription);

                                    $canceledSubscription->cancel();

                                    Subscription::where('stripe_subscription_id',$metaData->active_subscription)->update(['status'=>'canceled']);
                                }

                                $userJson = [
                                    'stripe_customer_id' => $authUser->stripe_customer_id,
                                    'name'  => $authUser->name,
                                    'email' => $authUser->email,
                                    'phone' => $authUser->phone,
                                    'register_type' => $authUser->register_type,
                                    'email_verified_at' => $authUser->email_verified_at,
                                    'phone_verified_at' => $authUser->phone_verified_at,
                                ];

                                $planJson = BuyerPlan::find($authUser->buyerDetail->plan_id);

                                BuyerTransaction::create([
                                    'user_id' => $authUser->id,
                                    'user_json' => json_encode($userJson),
                                    'plan_id'   => $planJson->id ?? null,
                                    'plan_json' =>  $planJson->toJson() ?? null,
                                    'payment_intent_id' => $paymentIntent->payment_intent,
                                    'amount' => (float)$paymentIntent->total / 100,
                                    'currency' => $paymentIntent->currency,
                                    'payment_method' => $paymentIntent->payment_method_types[0] ?? null,
                                    'payment_type'   => 'credit',
                                    'status' => 'success',
                                    'payment_json' => json_encode($event),
                                ]);

                                //Start Subscription
                                 $this->subscriptionEntry($authUser,'buyer', 'save', $planJson, $paymentIntent);
                                //End Subscription
                            }

                        }
                    
                    }

                    break;

                case 'invoice.payment_failed':

                    Log::info('Payment failed!');

                    // Handle successful payment event
                    $paymentIntent = $event->data->object;

                    $customer_stripe_id = $paymentIntent->customer;

                    $metaData = $paymentIntent->subscription_details->metadata ?? null;

                    if($metaData && $metaData->user_type){

                        if($metaData->user_type == 'seller'){
                            $customer = User::where('stripe_customer_id', $customer_stripe_id)->first();
                            $customerId = $customer->id ?? null;
        
                            $isAddon = false;
                            $planId = Plan::where('plan_stripe_id', $paymentIntent->lines->data[0]->plan->id)->value('id');
                            if (!$planId) {
                                $planId = Addon::where('product_stripe_id', $paymentIntent->lines->data[0]->plan->id)->value('id');
                                $isAddon = true;
                            }
        
                            $transaction = Transaction::where('user_id', $customerId)->where('payment_intent_id', $paymentIntent->payment_intent)->where('status','failed')->exists();
                            if (!$transaction) {
                                if (!is_null($customerId) && !is_null($planId)) {
                                    // Save data to transactions table
                                    Transaction::create([
                                        'user_id' => $customerId,
                                        'plan_id' => $planId,
                                        'is_addon' => $isAddon,
                                        'payment_intent_id' => $paymentIntent->payment_intent,
                                        'amount' => (float)$paymentIntent->lines->data[0]->amount / 100,
                                        'currency' => $paymentIntent->lines->data[0]->currency,
                                        'payment_method' => null,
                                        'payment_type'   => 'credit',
                                        'status' => 'failed',
                                        'payment_json' => json_encode($event),
                                    ]);
        
                                    $customer->level_type = 1;
                                    $customer->save();
                                }
                            }
                        }
                        
                        else if($metaData->product_type == 'boost-plan' && $metaData->user_type == 'buyer'){
                            $authUser = User::where('stripe_customer_id', $customer_stripe_id)->first();

                            $transaction = BuyerTransaction::where('user_id', $authUser->id)->where('payment_intent_id', $paymentIntent->payment_intent)->where('status','failed')->exists();

                            if (!$transaction) {

                                $userJson = [
                                    'stripe_customer_id' => $authUser->stripe_customer_id,
                                    'name'  => $authUser->name,
                                    'email' => $authUser->email,
                                    'phone' => $authUser->phone,
                                    'register_type' => $authUser->register_type,
                                    'email_verified_at' => $authUser->email_verified_at,
                                    'phone_verified_at' => $authUser->phone_verified_at,
                                ];

                                $planJson = BuyerPlan::find($authUser->buyerDetail->plan_id);

                                BuyerTransaction::create([
                                    'user_id' => $authUser->id,
                                    'user_json' => json_encode($userJson),
                                    'plan_id'   => $planJson->id ?? null,
                                    'plan_json' =>  $planJson->toJson() ?? null,
                                    'payment_intent_id' => $paymentIntent->payment_intent,
                                    'amount' => (float)$paymentIntent->total / 100,
                                    'currency' => $paymentIntent->currency,
                                    'payment_method' => $paymentIntent->payment_method_types[0] ?? null,
                                    'payment_type'   => 'credit',
                                    'status' => 'failed',
                                    'payment_json' => json_encode($event),
                                ]);

                            }
                        }
                    }
                   

                    break;

                case 'checkout.session.completed':
                    Log::info('Single Payment successfully!');

                    //Start to Handle recursive successful payment
                    $paymentIntent = $event->data->object;

                    $customer_stripe_id = $paymentIntent->customer;

                    if (isset($paymentIntent->metadata->plan) && $paymentIntent->metadata->user_type == 'seller') {
                        $customerId = User::where('stripe_customer_id', $customer_stripe_id)->value('id');

                        $planId = Addon::where('price_stripe_id', $paymentIntent->metadata->plan)->value('id');

                        $transaction = Transaction::where('user_id', $customerId)->where('payment_intent_id', $paymentIntent->id)->exists();
                        if (!$transaction) {
                            if (!is_null($customerId) && !is_null($planId)) {
                                // Save data to transactions table
                                Transaction::create([
                                    'user_id' => $customerId,
                                    'plan_id' => $planId,
                                    'is_addon' => true,
                                    'payment_intent_id' => $paymentIntent->payment_intent,
                                    'amount' => (float)$paymentIntent->amount_total / 100,
                                    'currency' => $paymentIntent->currency,
                                    'payment_method' => $paymentIntent->payment_method_types[0],
                                    'payment_type'   => 'credit',
                                    'status' => 'success',
                                    'payment_json' => json_encode($event),
                                ]);
                            }
                        }
                    }
                    //End to Handle recursive successful payment

                    //Start Single payment
                    if (isset($paymentIntent->metadata->product_type) && isset($paymentIntent->metadata->user_type)) {
                        
                        //Start Application Fee
                        if ($paymentIntent->metadata->product_type == 'application_fee' && $paymentIntent->metadata->user_type == 'buyer') {

                            $user = User::where('stripe_customer_id', $customer_stripe_id)->first();
                            $user->buyerVerification()->update(['is_application_process' => 1]);

                            $user->buyerDetail()->update(['is_profile_verified' => 1]);

                            $authUser = User::where('stripe_customer_id', $customer_stripe_id)->first();
                            $userJson = [
                                'stripe_customer_id' => $authUser->stripe_customer_id,
                                'name'  => $authUser->name,
                                'email' => $authUser->email,
                                'phone' => $authUser->phone,
                                'register_type' => $authUser->register_type,
                                'email_verified_at' => $authUser->email_verified_at,
                                'phone_verified_at' => $authUser->phone_verified_at,
                            ];

                            BuyerTransaction::create([
                                'user_id' => $authUser->id,
                                'user_json' => json_encode($userJson),
                                'plan_id'   => null,
                                'plan_json' =>  json_encode(['title' => 'Application Fee']),
                                'payment_intent_id' => $paymentIntent->payment_intent,
                                'amount' => (float)$paymentIntent->amount_total / 100,
                                'currency' => $paymentIntent->currency,
                                'payment_method' => $paymentIntent->payment_method_types[0],
                                'payment_type'   => 'credit',
                                'status' => 'success',
                                'payment_json' => json_encode($event),
                            ]);
                        }
                        //End Applicatio Fee

                        //Start Profile Upgrade
                        if ($paymentIntent->metadata->product_type == 'profile_update' && $paymentIntent->metadata->user_type == 'buyer') {

                            $user = User::where('stripe_customer_id', $customer_stripe_id)->first();
                            $user->buyerDetail()->update(['is_profile_payment' => 1]);

                            $authUser = User::where('stripe_customer_id', $customer_stripe_id)->first();
                            $userJson = [
                                'stripe_customer_id' => $authUser->stripe_customer_id,
                                'name'  => $authUser->name,
                                'email' => $authUser->email,
                                'phone' => $authUser->phone,
                                'register_type' => $authUser->register_type,
                                'email_verified_at' => $authUser->email_verified_at,
                                'phone_verified_at' => $authUser->phone_verified_at,
                            ];

                            BuyerTransaction::create([
                                'user_id' => $authUser->id,
                                'user_json' => json_encode($userJson),
                                'plan_id'   => null,
                                'plan_json' =>  json_encode(['title' => 'Profile Fee']),
                                'payment_intent_id' => $paymentIntent->payment_intent,
                                'amount' => (float)$paymentIntent->amount_total / 100,
                                'currency' => $paymentIntent->currency,
                                'payment_method' => $paymentIntent->payment_method_types[0],
                                'payment_type'   => 'credit',
                                'status' => 'success',
                                'payment_json' => json_encode($event),
                            ]);
                        }
                        //End Profile Upgrade

                    }
                    //End Single payment

                    break;

                default:
                    Log::info('Invalid Event fired!');
            }
        } catch (\Exception $e) {

            // dd($e->getMessage().'->'.$e->getLine());
            return response()->json(['error' => $e->getMessage() . '->' . $e->getLine()], 400);
            // return response()->json(['error' => 'Something went wrong!'], 400);
        }

        Log::info('End stripe webhook');

        return response()->json(['success' => true]);
    }

    public function paymentHistory()
    {

        try {
            $userId = auth()->user()->id;
            $transactions = Transaction::select('id', 'user_id', 'plan_id', 'amount', 'currency', 'is_addon', 'status', 'payment_type', 'created_at')->where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(10);

            foreach ($transactions as $transaction) {
                $transaction->user_name = $transaction->user->name ?? '';

                if ($transaction->is_addon == 0) {
                    $transaction->plan_name = $transaction->plan->title ?? '';
                }

                if ($transaction->is_addon == 1) {
                    $transaction->plan_name = $transaction->addonPlan->title ?? '';
                }
            }
            return response()->json(['status' => true, 'result' => $transactions], 200);
        } catch (\Exception $e) {
            // dd($e->getMessage().'->'.$e->getLine());
            return response()->json(['error' => 'Something went wrong!'], 400);
        }
    }


    public function createProfileUpgradeSession(Request $request)
    {
        DB::beginTransaction();
        try {
            $userId = auth()->user()->id;
            $authUser = User::where('id', $userId)->first();
            if ($authUser) {

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
                    'product_type' => 'profile_update',
                    'description'  => 'Profile Upgrade Fee Payment',
                ];

                $sessionData = [
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'price' => config('constants.buyer_profile_update_price_id'),
                            'quantity' => 1,
                        ],
                    ],
                    'mode' => 'payment',
                    'metadata' => $metadata,
                    'success_url' => env('FRONTEND_URL') . 'buyer-profile',
                    'cancel_url' => env('FRONTEND_URL') . 'profile-verification',
                ];


                // If customer ID is provided, set it in the session data
                if ($customer) {
                    $sessionData['customer'] = $customer->id;
                }

                // Create a Checkout Session
                $session = StripeSession::create($sessionData);

                DB::commit();

                return response()->json(['status' => true, 'session' => $session], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            //  dd($e->getMessage().'->'.$e->getLine());

            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => $e->getMessage() . '->' . $e->getLine(),
            ];
            return response()->json($responseData, 400);
        }
    }

    public function updateAutoRenewFlag(Request $request)
    {
        $request->validate([
            'is_plan_auto_renew' => ['required', 'in:0,1']
        ]);
        
        DB::beginTransaction();
        try {
            $authUser =  auth()->user();
            $authBuyerUser = Buyer::where('buyer_user_id',$authUser->id)->first();

            if($authBuyerUser){

                // Start to cancel subscription on stripe
                $isCancelSubscription = $this->subscriptionEntry($authUser,'buyer', 'update');
           
                //End to acncel subscription on stripe
                if($isCancelSubscription){
                    $updateBuyerPlan = Buyer::where('buyer_user_id',$authUser->id)->update(['is_plan_auto_renew' => $request->is_plan_auto_renew]);
    
                    DB::commit();
                    $responseData = [
                    'status'       => true,
                    'message' => ($request->is_plan_auto_renew == 1 ? 'The plan auto-renew is activated.':'The plan auto-renew is deactivated.')
                    ];
                    return response()->json($responseData, 200);
                }
            }else{
                 //Return Error Response
                $responseData = [
                    'status'        => false,
                    'error'         => trans('messages.error_message'),
                ];
                return response()->json($responseData, 400);
            }
            
        } catch (\Exception $e) {
            
            DB::rollBack();

            // dd($e->getMessage() . '->' . $e->getLine());

            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 400);
        }
    }

    public function subscriptionEntry($user, $userType='seller', $action='save', $plan=null, $paymentIntent=null){

        if($userType == 'seller'){

        }else if($userType == 'buyer'){
            
            if($action == 'update'){

                // Retrieve customer's subscriptions
                $getAllSubscriptions = StripeSubscription::all(['customer' => $user->stripe_customer_id]);

                // Find the active subscription
                $activeSubscription = null;
                foreach ($getAllSubscriptions as $subscription) {
                    if ($subscription->status === 'active') {
                        $activeSubscription = $subscription;
                        break;
                    }
                }

                if($activeSubscription){
                    // Cancel the active subscription
                    $canceledSubscription = StripeSubscription::retrieve($activeSubscription->id);
                    $canceledSubscription->cancel();
                }

            }else{
                $subscription = Subscription::where('stripe_subscription_id',$paymentIntent->subscription)->exists();

                if(!$subscription){
                    $stripe_subscription = StripeSubscription::retrieve($paymentIntent->subscription); 
                    
                    $record = [
                        'user_id' => $user->id,
                        'stripe_customer_id' => $paymentIntent->customer,
                        'plan_id'=>$plan->id,
                        'stripe_plan_id'=>$plan->plan_stripe_id,
                        'stripe_subscription_id'=>$paymentIntent->subscription,
                        'start_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_start)->format('Y-m-d'),
                        'end_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_end)->format('Y-m-d'),
                        'subscription_json'=>json_encode($stripe_subscription),
                        'status'=> $stripe_subscription->status,
                    ];

                    // return response()->json(['success' => true,'record'=>$stripe_subscription]);

                    Subscription::create($record);
                }
                
            }
        }

        return true;

    }

}
