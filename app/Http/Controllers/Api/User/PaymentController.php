<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Customer;
use App\Models\Plan;
use App\Models\Addon;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Buyer;
use App\Models\BuyerPlan;
use App\Models\BuyerTransaction;
use App\Models\Subscription;
use App\Models\UserToken;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Subscription as StripeSubscription;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            if (is_null($authUser->stripe_customer_id)) {
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
                    'success_url' => env('FRONTEND_URL') . 'payment-confirm/' . $token,
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
                    $updateBuyerPlan = User::where('id', $authUser->id)->update(['plan_id' => $buyerPlan->id, 'is_plan_auto_renew' =>1]);
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
                $authUser->prev_level_type = 2;
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
                $is_auto_renew = $request->is_plan_auto_renew == 1 ? true : false;

                $isCancelSubscription = $this->subscriptionEntry($authUser,'buyer', 'update',$is_auto_renew);
           
                //End to acncel subscription on stripe
                if($isCancelSubscription){
                    $updateBuyerPlan = User::where('id',$authUser->id)->update(['is_plan_auto_renew' => $request->is_plan_auto_renew]);
    
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

    public function subscriptionEntry($user, $userType='seller', $action='save', $is_auto_renew, $plan=null, $paymentIntent=null){

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
                    $canceledSubscription = StripeSubscription::update(
                        $activeSubscription->id,
                        ['cancel_at_period_end' => $is_auto_renew]
                    );

                    Subscription::where('stripe_subscription_id',$activeSubscription->id)->update(['status'=>'canceled']);
                }

            }else{
                $subscription = Subscription::where('stripe_subscription_id',$paymentIntent->subscription)->exists();

                if(!$subscription){
                    $stripe_subscription = StripeSubscription::retrieve($paymentIntent->subscription); 
                    
                    $record = [
                        'user_id'                   => $user->id,
                        'stripe_customer_id'        => $paymentIntent->customer,
                        'plan_id'                   => $plan ? $plan->id : null,
                        'stripe_plan_id'            => $plan ? $plan->plan_stripe_id : null,
                        'stripe_subscription_id'    => $paymentIntent->subscription,
                        'start_date'                => Carbon::createFromTimestamp($stripe_subscription->current_period_start)->format('Y-m-d'),
                        'end_date'                  => Carbon::createFromTimestamp($stripe_subscription->current_period_end)->format('Y-m-d'),
                        'subscription_json'         => json_encode($stripe_subscription),
                        'status'                    => $stripe_subscription->status,
                    ];

                    // return response()->json(['success' => true,'record'=>$stripe_subscription]);

                    Subscription::create($record);
                }
                
            }
        }

        return true;

    }

}
