<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyerInvitation extends Model
{
    use SoftDeletes;

    public $table = 'buyer_invitations';

    protected $dates = [
        'last_reminder_sent',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'uuid',
        'email',
        'reminder_count',
        'last_reminder_sent',
        'status',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
   

    protected static function boot () 
    {
        parent::boot();
        static::creating(function(BuyerInvitation $model) {
            $model->uuid = Str::uuid();
            $model->created_by = auth()->user()->id;
        });               
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }
}
