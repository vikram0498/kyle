<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Notification extends Model
{
    protected $table = "notifications";

    protected $primarykey = "id";

    protected $fillable = [
        'id',
        'type',
        'notifiable',
        'data',
        'read_at',        
    ];

    protected $dates = [
        'updated_at',
        'created_at',
       
    ];

    protected $casts = [
        'data' => 'array',
        'id' => 'string'
    ];


    public function notifyUser()
    {
        return $this->belongsTo(User::class,'notifiable_id','id');
    }


}
