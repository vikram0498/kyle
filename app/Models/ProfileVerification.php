<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileVerification extends Model
{
    use SoftDeletes;

    public $table = 'profile_verifications';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'other_proof_of_fund',
        'is_phone_verification',
        'is_driver_license',
        'is_proof_of_funds',
        'is_llc_verification',
        'is_application_process',
    ];

    public function uploads()
    {
        return $this->morphMany(Uploads::class, 'uploadsable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
