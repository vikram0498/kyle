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
        'country',
        'city',
        'state',
        'zip_code',
        'price',
        'bedroom',
        'bath',
        'size',
        'lot_size',
        'build_year',
        'arv',
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
        'squatters',
        'purchase_method',
        'max_down_payment_percentage',
        'max_down_payment_money',
        'max_interest_rate',
        'balloon_payment',
        'total_units',
        'unit_min',
        'unit_max',
        'building_class',
        'value_add',
        'stories',
        'zoning',
        'utilities',
        'sewer',
        'market_preferance',
        'contact_preferance',
        'permanent_affix',
        'park',
        'rooms',
        'status',
        'picture_link',
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

    public function uploads()
    {
        return $this->morphMany(Uploads::class, 'uploadsable')->where('type','search-log');;
    }

    public function getSearchLogImageUrlsAttribute()
    {
        $searchLogImages = [];
        if ($this->uploads) {
            foreach($this->uploads()->get() as $searchLogImage){
                if(!empty($searchLogImage->file_url)){
                    $searchLogImages[] = $searchLogImage->file_url;
                }
            }
            return $searchLogImages;
        }
        return "";
    }

    public function buyerDeals(){
        return $this->hasMany(BuyerDeal::class, 'search_log_id', 'id');
    }
}
