<?php

namespace App\Listeners;

use App\Events\SubscriptionChanged;
use Stripe\Stripe;
use Stripe\Subscription as StripSubscription;
use App\Models\Subscription as SubscriptionModel;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleSubscriptionChange implements ShouldQueue
{
    public function handle(SubscriptionChanged $event)
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));

        // Fetch all active subscriptions associated with the given plan_id
        $subscriptions = SubscriptionModel::where('plan_id', $event->planId)
                                     ->where('status', 'active')
                                     ->get();

        foreach ($subscriptions as $subscription) {
            $stripeSubscriptionId = $subscription->stripe_subscription_id;

            if ($event->action === 'cancel') {
                $this->cancelSubscription($stripeSubscriptionId);
            }

            if ($event->action === 'update') {
                $this->updateSubscriptionPrice($stripeSubscriptionId, $event->oldPriceId, $event->newPriceId);    
            }
        }
    }

    protected function cancelSubscription($stripeSubscriptionId)
    {
        try {
            // Retrieve the subscription from Stripe
            $subscription = StripeSubscription::retrieve($stripeSubscriptionId);

            // Cancel the subscription
            $subscription->cancel();

            // Optionally, you can log or notify the user that their subscription has been canceled
            \Log::info("Subscription {$stripeSubscriptionId} canceled successfully.");
        } catch (\Exception $e) {
            // Handle any errors that occur when canceling
            \Log::error("Error canceling subscription {$stripeSubscriptionId}: " . $e->getMessage());
        }
    }

    // Update the subscription's price in Stripe
    protected function updateSubscriptionPrice($stripeSubscriptionId, $oldPriceId, $newPriceId)
    {
        try {
            // Retrieve the subscription from Stripe
            $subscription = StripeSubscription::retrieve($stripeSubscriptionId);

            // Loop through the subscription items to find the one with the old price
            foreach ($subscription->items->data as $item) {
                if ($item->price->id === $oldPriceId) {
                    // Update the subscription to the new price
                    StripeSubscription::update($stripeSubscriptionId, [
                        'items' => [
                            [
                                'id' => $item->id,
                                'price' => $newPriceId,
                            ],
                        ],
                        'proration_behavior' => 'create_prorations', // Handle prorations for mid-cycle price changes
                    ]);

                    // Optionally, log or notify the user about the price change
                    \Log::info("Subscription {$stripeSubscriptionId} updated with new price {$newPriceId}.");
                    break;
                }
            }
        } catch (\Exception $e) {
            // Handle any errors that occur when updating the subscription
            \Log::error("Error updating subscription {$stripeSubscriptionId}: " . $e->getMessage());
        }
    }
}

