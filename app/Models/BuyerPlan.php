<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyerPlan extends Model
{
    use SoftDeletes;

    protected $casts = [];

    public $table = 'buyer_plans';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'plan_stripe_id',
        'plan_json',
        'title',
        'type',
        'position',
        'amount',
        'description',
        'status',
        'user_limit',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot () 
    {
        parent::boot();
        static::creating(function(BuyerPlan $model) {
            $model->created_by = auth()->user() ? auth()->user()->id : null;
        });               
    }

    
    public function uploads()
    {
        return $this->morphMany(Uploads::class, 'uploadsable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function planImage()
    {
        return $this->morphOne(Uploads::class, 'uploadsable')->where('type','buyer-plan');
    }

    public function getImageUrlAttribute()
    {
        if($this->planImage){
            return $this->planImage->file_url;
        }
        return "";
    }

    public function buyers()
    {
        return $this->hasMany(Buyer::class, 'plan_id', 'id');
    }

}
