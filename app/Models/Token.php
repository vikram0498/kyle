<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model
{
    use SoftDeletes;

    public $table = 'tokens';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'token_value',
        'token_expired_time',
        'is_used',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
   

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
