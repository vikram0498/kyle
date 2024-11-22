<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvertiseBanner extends Model
{
    use SoftDeletes;

    public $table = 'advertise_banners';

    public $timestamps = true;
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'start_date',
        'end_date',
    ];

    protected $casts = [        
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',        
    ];

    protected $fillable = [
        'advertiser_name',
        'ad_name',
        'target_url',
        'impressions_purchased',
        'impressions_served',
        'impressions_count',
        'click_count',
        'start_date',
        'end_date',
        'page_type',
        'start_time',
        'end_time',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function boot () 
    {
        parent::boot();
        static::creating(function(AdvertiseBanner $model) {
            $model->created_by = auth()->user()->id;
        });   
        
        static::deleting(function (AdvertiseBanner $model) {
            $model->deleted_by = auth()->user()->id;
            $model->save();
        });

        static::updating(function(AdvertiseBanner $model) {
            $model->updated_by = auth()->user()->id;
        });
    }

    public function uploads()
    {
        return $this->morphMany(Uploads::class, 'uploadsable');
    }

    public function adBannerImage()
    {
        return $this->morphOne(Uploads::class, 'uploadsable')->where('type','adBanner');
    }

    public function getImageUrlAttribute()
    {
        if($this->adBannerImage){
            return $this->adBannerImage->file_url;
        }
        return "";
    }

    public function adPerformaceLogs(){
        return $this->hasMany(AdPerformanceLog::class, 'advertise_banner_id');
    }
}
