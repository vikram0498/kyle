<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SearchLog extends Model
{
    use SoftDeletes;

    protected $casts = ['property_flaw' => 'array', 'purchase_method' => 'array'];

    public $table = 'search_logs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'address',
        'city',
        'state',
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
        'purchase_method',
        'status',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot () 
    {
        parent::boot();
        static::creating(function(SearchLog $model) {
            $model->created_by = auth()->user()->id;
        });               
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
