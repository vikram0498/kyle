<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailMail;

class User extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes, Notifiable, HasApiTokens;

    protected $guard = 'web';

    public $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'phone',
        'register_type',
        'social_id',
        'social_json',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'is_active',
        'is_block',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function uploads()
    {
        return $this->morphMany(Uploads::class, 'uploadsable');
    }

    public function redFlagedBuyer(){
        return $this->belongsToMany(Buyer::class)->withPivot('reason', 'status');
    }

    public function buyers()
    {
        return $this->hasMany(Buyer::class, 'user_id', 'id');
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function getIsSellerAttribute()
    {
        return $this->roles()->where('id', 2)->exists();
    }

    public function profileImage()
    {
        return $this->morphOne(Uploads::class, 'uploadsable')->where('type','profile');
    }

    public function getProfileImageUrlAttribute()
    {
        if($this->profileImage){
            return $this->profileImage->file_url;
        }
        return "";
    }
   
    public function NotificationSendToVerifyEmail (){
        $user = $this;
        
        $url = config('constants.front_end_url').'email/verify/'.$user->id.'/'.sha1($user->email);

        $subject = 'Verify Email Address';

        Mail::to($user->email)->queue(new VerifyEmailMail($user->name, $url, $subject));
    }
}
