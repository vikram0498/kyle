<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Video extends Model
{
    use SoftDeletes;

    public $table = 'videos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'video_key',
        'title',
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
        static::creating(function(Video $model) {
            $model->created_by = auth()->user()->id;
        });               
    }

    public function uploads()
    {
        return $this->morphMany(Uploads::class, 'uploadsable');
    }

    public function uploadedVideo()
    {
        return $this->morphOne(Uploads::class, 'uploadsable')->where('type','video');
    }

    public function getVideoUrlAttribute()
    {
        if($this->uploadedVideo){
            return $this->uploadedVideo->file_url;
        }
        return "";
    }
}
