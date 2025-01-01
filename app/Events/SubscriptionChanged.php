<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class SubscriptionChanged
{
    use SerializesModels;

    public $planId;  // The plan_id to fetch associated subscriptions
    public $action;   // 'update' or 'cancel'
    public $oldPriceId; // Old price ID (for price update actions)
    public $newPriceId; // New price ID (for price update actions)

    public function __construct($planId, $action, $oldPriceId = null, $newPriceId = null)
    {
        $this->planId = $planId;
        $this->action = $action;
        $this->oldPriceId = $oldPriceId;
        $this->newPriceId = $newPriceId;
    }
}
