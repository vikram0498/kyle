<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyerDeal extends Model
{
    use SoftDeletes;

    public $table = 'buyer_deals';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'uuid',
        'buyer_user_id',
        'search_log_id',
        'message',
        'buyer_feedback',
        'status',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
   

    protected static function boot () 
    {
        parent::boot();
        static::creating(function(BuyerDeal $model) {
            $model->uuid = Str::uuid();
            $model->created_by = auth()->user()->id;
        });               
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by', 'id')->withTrashed();
    }

    public function searchLog(){
        return $this->belongsTo(SearchLog::class,'search_log_id', 'id');
    }

    public function buyerUser(){
        return $this->belongsTo(User::class,'buyer_user_id', 'id');
    }
}
