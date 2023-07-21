<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Buyer extends Model
{
    use SoftDeletes;

    protected $casts = ['parking' => 'array', 'property_type' => 'array', 'property_flaw' => 'array', 'buyer_type' => 'array', 'building_class' => 'array', 'purchase_method' => 'array'];

    public $table = 'buyers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'occupation',
        'replacing_occupation',
        'company_name',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'bedroom_min',
        'bedroom_max',
        'bath_min',
        'bath_max',
        'size_min',
        'size_max',
        'lot_size_min',
        'lot_size_max',
        'build_year_min',
        'build_year_max',
        'arv_min',
        'arv_max',
        'parking',
        'property_type',
        'property_flaw',
        'solar',
        'pool',
        'septic',
        'well',
        'age_restriction',
        'rental_restriction',
        'hoa',
        'tenant',
        'post_possession',
        'building_required',
        'foundation_issues',
        'mold',
        'fire_damaged',
        'rebuild',
        'buyer_type',
        'max_down_payment_percentage',
        'max_down_payment_money',
        'max_interest_rate',
        'balloon_payment',
        'unit_min',
        'unit_max',
        'building_class',
        'value_add',
        'purchase_method',
        'is_ban',
        'status',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot () 
    {
        parent::boot();
        static::creating(function(Buyer $model) {
            $model->created_by = auth()->user()->id;
        });               
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function redFlagedData(){
        return $this->belongsToMany(User::class)->withPivot('reason', 'status');
    }
}
