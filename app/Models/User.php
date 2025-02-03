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
        'is_online',
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
        'plan_id',
        'is_plan_auto_renew',
        'is_profile_verified',
        'level_type',
        'prev_level_type',
        'level_3',
        'is_switch_role',
        'is_super_buyer',
        'original_role_id',
        'is_active',
        'is_block',
        'device_token',
        'login_at',
        'email_verified_at',
        'phone_verified_at',
        'is_online',
        'status', // this status for buyer will search or not,
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

    public function originalRole()
    {
        return $this->belongsTo(Role::class, 'original_role_id');
    }

    public function uploads()
    {
        return $this->morphMany(Uploads::class, 'uploadsable');
    }

    public function redFlagedBuyer(){
        return $this->belongsToMany(Buyer::class)->withPivot(['reason', 'status']);
    }

    public function buyerPlan()
    {
        return $this->belongsTo(BuyerPlan::class, 'plan_id', 'id');
    }

    public function buyers()
    {
        return $this->hasMany(Buyer::class, 'user_id', 'id');
    }

    public function buyerDetail()
    {
        return $this->hasOne(Buyer::class, 'buyer_user_id', 'id');
    }

    public function buyerProperties()
    {
        return $this->hasMany(Buyer::class, 'buyer_user_id', 'id');
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

        $path = config('constants.default.profile_image');
        $defaultProfileImageUrl = asset($path);

        return $defaultProfileImageUrl;
    }

    /** Start Buyer Profile Verification files */

    public function getIsBuyerVerifiedAttribute()
    {
        return $this->buyerVerification()->where('is_phone_verification', 1)
        ->where('is_driver_license',1)->where('driver_license_status','verified')
        ->where('is_proof_of_funds', 1)->where('proof_of_funds_status','verified')
        ->where('is_llc_verification',1)->where('llc_verification_status','verified')
        ->where('is_certified_closer',1)->where('certified_closer_status','verified')
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

    //Start Certified Closer Statement Pdf
    public function certifiedCloserPdf()
    {
        return $this->morphOne(Uploads::class, 'uploadsable')->where('type','certified-closer-pdf');
    }

    public function getCertifiedCloserPdfUrlAttribute()
    {
        if($this->certifiedCloserPdf){
            return $this->certifiedCloserPdf->file_url;
        }
        return "";
    }
    //End Certified Closer Statement Pdf

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

    public function seenMessage()
    {
        return $this->belongsToMany(Message::class, 'message_seen', 'user_id','message_id')
        ->withPivot('conversation_id', 'read_at');
    }


    public function wishlistedUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists', 'user_id', 'wishlist_user_id')
            ->withTimestamps();
    }

    public function addedToWishlists()
    {
        return $this->belongsToMany(User::class, 'wishlists', 'wishlist_user_id', 'user_id')
            ->withTimestamps();
    }

    public function blockedUsers()
    {
        return $this->belongsToMany(
            User::class,
            'user_block',
            'user_id',
            'blocked_by'
        )->withPivot('blocked_at', 'block_status')->withTimestamps();
    }

    public function blockedByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'user_block',
            'blocked_by',
            'user_id',
        )->withPivot('blocked_at', 'block_status')->withTimestamps();
    }

    public function isBlockedByAuthUser()
    {
        return $this->blockedUsers()
            ->where('blocked_by', auth()->user()->id)
            ->where('block_status', 1)
            ->exists();
    }

}
