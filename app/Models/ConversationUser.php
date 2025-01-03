<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversationUser extends Model
{
    use SoftDeletes;

    public $table = 'conversation_user';

    protected $fillable = [
        'conversation_id',
        'user_id',
        'is_block',
        'blocked_at',
        'blocked_by',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

   
    public function conversation()
    {
        return $this->belongsTo(Conversation::class,'conversation_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function blockedBy()
    {
        return $this->belongsTo(User::class,'blocked_by','id');
    }

}
