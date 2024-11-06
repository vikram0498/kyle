<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationSetting extends Model
{
    use SoftDeletes;

    public $table = 'notification_settings';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $fillable = [
        'user_id',
        'key',
        'value',
        'display_name',
        'user_type',
        'push_notification',
        'email_notification',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
