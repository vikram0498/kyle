<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Message extends Model
{
    use SoftDeletes;

    public $table = 'messages';

    protected $fillable = [
        'uuid',
        'conversation_id',
        'sender_id',
        'receiver_id',
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

            $cacheKey = "conversation_messages_{$model->id}";
            Cache::forget($cacheKey);
        });               
    }

    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id','id');
    }

    public function receiver()
    { 
        return $this->belongsTo(User::class,'receiver_id','id');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    public function seenBy()
    {
        return $this->belongsToMany(User::class, 'message_seen', 'message_id', 'user_id')
                    ->withPivot('conversation_id', 'read_at');
    }

}
