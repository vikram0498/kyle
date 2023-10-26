<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class BuyerTransaction extends Model
{
    use SoftDeletes;

    public $table = 'buyer_transactions';
  
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'user_json',
        'plan_id',
        'plan_json',
        'payment_intent_id',
        'amount',
        'currency',
        'payment_method',
        'payment_type',
        'payment_json',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(BuyerPlan::class);
    }
}
