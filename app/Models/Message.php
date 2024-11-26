<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Message extends Model
{
    use SoftDeletes;

    public $table = 'messages';

    protected $fillable = [
        'uuid',
        'conversation_id',
        'user_id',
        'content',
        'type',
        'chat_type',        
        // 'group_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot () 
    {
        parent::boot();
        static::creating(function(Message $model) {
            $model->uuid = Str::uuid();
        });               
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function usersSeen()
    {
        return $this->belongsToMany(User::class, 'message_seen', 'message_id','user_id')
        ->withPivot('conversation_id', 'read_at');
    }

}
