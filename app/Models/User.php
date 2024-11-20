<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailMail;
use App\Mail\VerifyBuyerEmailMail;


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
        'stripe_customer_id',
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'country_code',
        'phone',
        'description',
        'company_name',
        'otp',
        'register_type',
        'social_id',
        'social_json',
        'created_at',
        'updated_at',
        'deleted_at',
	    'terms_accepted',
        'remember_token',
        'level_type',
        'prev_level_type',
        'level_3',
        'is_switch_role',
        'is_active',
        'is_block',
        'device_token',
        'login_at',
        'email_verified_at',
        'phone_verified_at',
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
        'phone_verified_at',
        'login_at',
    ];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }
    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function uploads()
    {
        return $this->morphMany(Uploads::class, 'uploadsable');
    }

    public function redFlagedBuyer(){
        return $this->belongsToMany(Buyer::class)->withPivot(['reason', 'status']);
    }

    public function buyers()
    {
        return $this->hasMany(Buyer::class, 'user_id', 'id');
    }

    public function buyerDetail()
    {
        return $this->hasOne(Buyer::class, 'buyer_user_id', 'id');
    }

    public function buyerVerification()
    {
        return $this->hasOne(ProfileVerification::class, 'user_id', 'id');
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function getIsSellerAttribute()
    {
        return $this->roles()->where('id', 2)->exists();
    }

    public function getIsBuyerAttribute()
    {
        return $this->roles()->where('id', 3)->exists();
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

    /** Start Buyer Profile Verification files */

    public function getIsBuyerVerifiedAttribute()
    {
        return $this->buyerVerification()->where('is_phone_verification', 1)
        ->where('is_driver_license',1)->where('driver_license_status','verified')
        ->where('is_proof_of_funds', 1)->where('proof_of_funds_status','verified')
        ->where('is_llc_verification',1)->where('llc_verification_status','verified')
        ->where('is_application_process',1)->exists();
    }

    // Start Driver License Images
    public function driverLicenseFrontImage()
    {
        return $this->morphOne(Uploads::class, 'uploadsable')->where('type','driver-license-front');
    }

    public function getDriverLicenseFrontImageUrlAttribute()
    {
        if($this->driverLicenseFrontImage){
            return $this->driverLicenseFrontImage->file_url;
        }
        return "";
    }

    public function driverLicenseBackImage()
    {
        return $this->morphOne(Uploads::class, 'uploadsable')->where('type','driver-license-back');
    }

    public function getDriverLicenseBackImageUrlAttribute()
    {
        if($this->driverLicenseBackImage){
            return $this->driverLicenseBackImage->file_url;
        }
        return "";
    }
    // End Driver License Images

    //Start Bank Statement Pdf
    public function bankStatementPdf()
    {
        return $this->morphOne(Uploads::class, 'uploadsable')->where('type','bank-statement-pdf');
    }

    public function getBankStatementPdfUrlAttribute()
    {
        if($this->bankStatementPdf){
            return $this->bankStatementPdf->file_url;
        }
        return "";
    }
    //End Bank Statement Pdf

    // Start LLC Front Images
    public function llcFrontImage()
    {
        return $this->morphOne(Uploads::class, 'uploadsable')->where('type','llc-front-image');
    }

    public function getLlcFrontImageUrlAttribute()
    {
        if($this->llcFrontImage){
            return $this->llcFrontImage->file_url;
        }
        return "";
    }

    public function llcBackImage()
    {
        return $this->morphOne(Uploads::class, 'uploadsable')->where('type','llc-back-image');
    }

    public function getLlcBackImageUrlAttribute()
    {
        if($this->llcBackImage){
            return $this->llcBackImage->file_url;
        }
        return "";
    }
    // End LLC Back Images

    /** End Buyer Profile Verification files */

   
    public function NotificationSendToVerifyEmail (){
        $user = $this;
        
        $url = config('constants.front_end_url').'email/verify/'.$user->id.'/'.sha1($user->email);

        $subject = "Welcome to ".config('app.name').", ".$user->first_name."! Verify Your Email to Access Your Account.";

        Mail::to($user->email)->queue(new VerifyEmailMail($user->first_name, $url, $subject));
    }

    public function NotificationSendToBuyerVerifyEmail(){
        $user = $this;
        
        $url = config('constants.front_end_url').'verify-and-setpassword/'.$user->id.'/'.sha1($user->email);

        $subject = "Welcome to ".config('app.name').", ".$user->first_name."! Verify Your Email to Access Your Account.";

        Mail::to($user->email)->queue(new VerifyBuyerEmailMail($user->first_name, $url, $subject));
    }


    public function copyTokens()
    {
        return $this->hasMany(Token::class);
    }

    public function purchasedBuyers()
    {
        return $this->hasMany(PurchasedBuyer::class,'user_id','id');
    }

    public function paymentToken(){
        return $this->hasMany(PaymentToken::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function subscription()
    {
        return $this->hasMany(Subscription::class,'user_id');
    }

    public function notification()
    {
        return $this->hasMany(Notification::class, 'notifiable_id');
    }

    public function notificationSetting(){
       
        return $this->hasOne(NotificationSetting::class, 'user_id', 'id');
    
    }
    
    public function getFullPhoneNumberAttribute()
    {
        if($this->country_code && $this->phone){
            return $this->country_code.'-'.$this->phone;
        }
        return "";
    }


}
