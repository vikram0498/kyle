<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use SoftDeletes;

    public $table = 'conversations';

    protected $fillable = [
        'uuid',
        'participant_1',
        'participant_2',
        'title',
        'is_group',
        'created_by',
        'participants_count',
        'last_message_at',
        'archived_at',        
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
        static::creating(function(Conversation $model) {
            $model->uuid = Str::uuid();
        });               
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function participantOne()
    {
        return $this->belongsTo(User::class, 'participant_1');
    }

    /**
     * Get the second participant (participant_2) of the conversation.
     */
    public function participantTwo()
    {
        return $this->belongsTo(User::class, 'participant_2');
    }

    public function conversationUsers()
    {
        return $this->hasMany(ConversationUser::class,'conversation_id');
    }

}
