<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBuyerLikes extends Model
{
    public $table = 'user_buyer_likes';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'buyer_id',
        'liked',
        'disliked',
        'created_at',
        'updated_at',
    ];
   

    public function buyerLikes()
    {
        return $this->belongsTo(Buyer::class);
    }
}
