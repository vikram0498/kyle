<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    use SoftDeletes;

    public $table = 'advertisements';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'campaign_id',
        'page_title',
        'target_zone',
        'target_url',
        'target_alt_des',
        'date_to_start',
        'date_to_end',
        'impression_count',
        'click_count',
        'status',
        'created_by',
        'updated_by',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
