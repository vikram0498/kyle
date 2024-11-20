<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdPerformanceLog extends Model
{
    use HasFactory;

    public $table = 'ad_performance_logs';

    public $timestamps = true;
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'advertise_banner_id',
        'event_type',
        'user_ip'        
    ];


    public function adBanner(){
        return $this->belongsTo(AdvertiseBanner::class, 'advertise_banner_id');
    }
}
