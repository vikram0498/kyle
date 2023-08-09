<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasedBuyer extends Model
{
    public $table = 'purchased_buyers';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'buyer_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id');
    }
}
