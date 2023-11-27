<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Subscription extends Model
{
    use SoftDeletes;

    public $table = 'subscriptions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'stripe_customer_id',
        'plan_id',
        'stripe_plan_id',
        'stripe_subscription_id',
        'start_date',
        'end_date',
        'subscription_json',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot () 
    {
        parent::boot();              
    }

    public function plan() {
        return $this->belongsTo(Plan::class);
    }

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
