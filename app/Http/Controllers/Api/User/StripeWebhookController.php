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
use App\Models\Buyer;
use App\Models\BuyerPlan;
use App\Models\UserToken;
use App\Models\Transaction;
use App\Models\Subscription;
use App\Models\BuyerTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Subscription as StripeSubscription;


class StripeWebhookController extends Controller
{
    
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
                    $this->handleInvoicePaymentSucceeded($event->data->object);
                break;

                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event->data->object);
                break;

                case 'checkout.session.completed':
                    $this->handleCheckoutSessionCompleted($event->data->object);
                break;

                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
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

    private function handleInvoicePaymentSucceeded($eventDataObject)
    {
        Log::info('Start stripe webhook of invoice payment succeeded');
       
        $customer_stripe_id = $eventDataObject->customer;

        $metaData = $eventDataObject->subscription_details->metadata ?? null;
        
        if ($metaData && $metaData->user_type) {

            if($metaData->user_type == 'seller'){
                $customerId = User::where('stripe_customer_id', $customer_stripe_id)->value('id');

                $isAddon = false;
                $planId = Plan::where('plan_stripe_id', $eventDataObject->lines->data[0]->plan->id)->value('id');
                if (!$planId) {
                    $planId = Addon::where('product_stripe_id', $eventDataObject->lines->data[0]->plan->id)->value('id');
                    $isAddon = true;
                }

                $transaction = Transaction::where('user_id', $customerId)->where('payment_intent_id', $eventDataObject->payment_intent)->where('status','success')->exists();
                if (!$transaction) {
                    if (!is_null($customerId) && !is_null($planId)) {
                        // Save data to transactions table
                        Transaction::create([
                            'user_id' => $customerId,
                            'plan_id' => $planId,
                            'is_addon' => $isAddon,
                            'payment_intent_id' => $eventDataObject->payment_intent,
                            'amount' => (float)$eventDataObject->lines->data[0]->amount / 100,
                            'currency' => $eventDataObject->lines->data[0]->currency,
                            'payment_method' => null,
                            'payment_type'   => 'credit',
                            'status' => 'success',
                            'payment_json' => json_encode($eventDataObject),
                        ]);
                    }
                }
                
            }else if($metaData->product_type == 'boost-plan' && $metaData->user_type == 'buyer'){
                $authUser = User::where('stripe_customer_id', $customer_stripe_id)->first();

                $buyerTransaction = BuyerTransaction::where('user_id', $authUser->id)->where('payment_intent_id', $eventDataObject->payment_intent)->where('status','success')->exists();

                // return response()->json(['success' => true,'payment_intent'=>'test']);

                if (!$buyerTransaction) {

                      // Retrieve customer's subscriptions
                        $getAllSubscriptions = StripeSubscription::all(['customer' => $customer_stripe_id]);

                        // Find the active subscription
                        $activeSubscription = isset($getAllSubscriptions['data'][1]) ? $getAllSubscriptions['data'][1] : null;
                        
                        // return response()->json(['success' => true,'payment_intent'=>$activeSubscription]);

                        if($activeSubscription){
                            // Cancel the active subscription
                            StripeSubscription::update(
                                $activeSubscription->id,
                                ['cancel_at_period_end' => false]
                            );
                            $canceledSubscription = StripeSubscription::retrieve($activeSubscription->id);
                            $canceledSubscription->cancel();
        
                            Subscription::where('stripe_subscription_id',$activeSubscription->id)->update(['status'=>'canceled']);
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

                    $planStripeId = $eventDataObject->lines->data[0]->plan->id;
                    $planJson = BuyerPlan::where('plan_stripe_id',$planStripeId)->first();

                    BuyerTransaction::create([
                        'user_id' => $authUser->id,
                        'user_json' => json_encode($userJson),
                        'plan_id'   => $planJson ? $planJson->id : null,
                        'plan_json' =>  $planJson ? $planJson->toJson() : null,
                        'payment_intent_id' => $eventDataObject->payment_intent,
                        'amount' => (float)$eventDataObject->total / 100,
                        'currency' => $eventDataObject->currency,
                        'payment_method' => $eventDataObject->payment_method_types[0] ?? null,
                        'payment_type'   => 'credit',
                        'status' => 'success',
                        'payment_json' => json_encode($eventDataObject),
                    ]);

                    //Start Subscription
                     $this->subscriptionEntry($authUser,'buyer', 'save', true, $planJson, $eventDataObject);
                    //End Subscription
                }

            }
        
        }
    }

    private function handleInvoicePaymentFailed($eventDataObject)
    {
        Log::info('Start stripe webhook of invoice payment failed');
       
        $customer_stripe_id = $eventDataObject->customer;

        $metaData = $eventDataObject->subscription_details->metadata ?? null;

        if($metaData && $metaData->user_type){

            if($metaData->user_type == 'seller'){
                $customer = User::where('stripe_customer_id', $customer_stripe_id)->first();
                $customerId = $customer->id ?? null;

                $isAddon = false;
                $planId = Plan::where('plan_stripe_id', $eventDataObject->lines->data[0]->plan->id)->value('id');
                if (!$planId) {
                    $planId = Addon::where('product_stripe_id', $eventDataObject->lines->data[0]->plan->id)->value('id');
                    $isAddon = true;
                }

                $transaction = Transaction::where('user_id', $customerId)->where('payment_intent_id', $eventDataObject->payment_intent)->where('status','failed')->exists();
                if (!$transaction) {
                    if (!is_null($customerId) && !is_null($planId)) {
                        // Save data to transactions table
                        Transaction::create([
                            'user_id' => $customerId,
                            'plan_id' => $planId,
                            'is_addon' => $isAddon,
                            'payment_intent_id' => $eventDataObject->payment_intent,
                            'amount' => (float)$eventDataObject->lines->data[0]->amount / 100,
                            'currency' => $eventDataObject->lines->data[0]->currency,
                            'payment_method' => null,
                            'payment_type'   => 'credit',
                            'status' => 'failed',
                            'payment_json' => json_encode($eventDataObject),
                        ]);

                        $customer->level_type = 1;
                        $customer->prev_level_type = 1;
                        $customer->save();
                    }
                }
            }
            
            else if($metaData->product_type == 'boost-plan' && $metaData->user_type == 'buyer'){
              /*  $authUser = User::where('stripe_customer_id', $customer_stripe_id)->first();

                $transaction = BuyerTransaction::where('user_id', $authUser->id)->where('payment_intent_id', $eventDataObject->payment_intent)->where('status','failed')->exists();

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
                        'plan_id'   => $planJson ? $planJson->id : null,
                        'plan_json' =>  $planJson ? $planJson->toJson() : null,
                        'payment_intent_id' => $eventDataObject->payment_intent,
                        'amount' => (float)$eventDataObject->total / 100,
                        'currency' => $eventDataObject->currency,
                        'payment_method' => $eventDataObject->payment_method_types[0] ?? null,
                        'payment_type'   => 'credit',
                        'status' => 'failed',
                        'payment_json' => json_encode($eventDataObject),
                    ]);

                }
                */
            }
        }

    }

    private function handleCheckoutSessionCompleted($eventDataObject)
    {
        Log::info('Start stripe webhook of checkout session completed');
        //Start to Handle recursive successful payment

        $customer_stripe_id = $eventDataObject->customer;

        if (isset($eventDataObject->metadata->plan) && $eventDataObject->metadata->user_type == 'seller') {
            $customerId = User::where('stripe_customer_id', $customer_stripe_id)->value('id');

            $planId = Addon::where('price_stripe_id', $eventDataObject->metadata->plan)->value('id');

            $transaction = Transaction::where('user_id', $customerId)->where('payment_intent_id', $eventDataObject->payment_intent)->exists();
            if (!$transaction) {
                if (!is_null($customerId) && !is_null($planId)) {
                    // Save data to transactions table
                    Transaction::create([
                        'user_id' => $customerId,
                        'plan_id' => $planId,
                        'is_addon' => true,
                        'payment_intent_id' => $eventDataObject->payment_intent,
                        'amount' => (float)$eventDataObject->amount_total / 100,
                        'currency' => $eventDataObject->currency,
                        'payment_method' => $eventDataObject->payment_method_types[0],
                        'payment_type'   => 'credit',
                        'status' => 'success',
                        'payment_json' => json_encode($eventDataObject),
                    ]);
                }
            }
        }
        //End to Handle recursive successful payment

        //Start Single payment
        if (isset($eventDataObject->metadata->product_type) && isset($eventDataObject->metadata->user_type)) {
            
            //Start Application Fee
            if ($eventDataObject->metadata->product_type == 'application_fee' && $eventDataObject->metadata->user_type == 'buyer') {

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
                    'payment_intent_id' => $eventDataObject->payment_intent,
                    'amount' => (float)$eventDataObject->amount_total / 100,
                    'currency' => $eventDataObject->currency,
                    'payment_method' => $eventDataObject->payment_method_types[0],
                    'payment_type'   => 'credit',
                    'status' => 'success',
                    'payment_json' => json_encode($eventDataObject),
                ]);
            }
            //End Applicatio Fee

        }
        //End Single payment
    }

    private function handleSubscriptionDeleted($eventDataObject){
        return response()->json(['status'=>true,'message' => 'Canceled subscription'], 200);

        Log::info('Start stripe webhook of customer subscription deleted');  
    }


    private function subscriptionEntry($user, $userType='seller', $action='save', $is_auto_renew, $plan=null, $eventDataObject=null){

        if($userType == 'seller'){

        }else if($userType == 'buyer'){
            
            $subscription = Subscription::where('stripe_subscription_id',$eventDataObject->subscription)->exists();

            if(!$subscription){
                $stripe_subscription = StripeSubscription::retrieve($eventDataObject->subscription); 
                
                $record = [
                    'user_id' => $user->id,
                    'stripe_customer_id' => $eventDataObject->customer,
                    'plan_id'=>$plan ? $plan->id : null,
                    'stripe_plan_id'=>$plan ? $plan->plan_stripe_id : null,
                    'stripe_subscription_id'=>$eventDataObject->subscription,
                    'start_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_start)->format('Y-m-d'),
                    'end_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_end)->format('Y-m-d'),
                    'subscription_json'=>json_encode($stripe_subscription),
                    'status'=> $stripe_subscription->status,
                ];

                // return response()->json(['success' => true,'record'=>$stripe_subscription]);

                Subscription::create($record);
            }
                
        }

        return true;

    }


}
