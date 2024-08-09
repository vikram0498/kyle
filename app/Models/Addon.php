<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Addon extends Model
{
    use SoftDeletes;

    public $table = 'addons';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'product_stripe_id',
        'price_stripe_id',
        'title',
        'price',
        'credit',
        'position',
        'product_json',
        'price_json',
        'status',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot () 
    {
        parent::boot();
        static::creating(function(Addon $model) {
            $model->created_by = auth()->user()->id;
        });               
    } 
}
