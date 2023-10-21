<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    public $table = 'transactions';
  
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'plan_id',
        'is_addon',
        'payment_intent_id',
        'amount',
        'currency',
        'status',
        'payment_type',
        'payment_method',
        'payment_json',
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
        return $this->belongsTo(Plan::class);
    }

    public function addonPlan()
    {
        return $this->belongsTo(Addon::class,'plan_id');
    }
}
