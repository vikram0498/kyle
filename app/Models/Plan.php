<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Plan extends Model
{
    use SoftDeletes;

    public $table = 'plans';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'month_amount',
        'year_amount',
        'monthly_credit',
        'description',
        'status',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot () 
    {
        parent::boot();
        static::creating(function(Plan $model) {
            $model->created_by = auth()->user()->id;
        });               
    }

    public function uploads()
    {
        return $this->morphMany(Uploads::class, 'uploadsable');
    }

    public function packageImage()
    {
        return $this->morphOne(Uploads::class, 'uploadsable')->where('type','plan');
    }

    public function getImageUrlAttribute()
    {
        if($this->packageImage){
            return $this->packageImage->file_url;
        }
        return "";
    }
}
