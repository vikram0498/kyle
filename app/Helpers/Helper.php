<?php

use App\Models\NotificationSetting;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Uploads;
use Illuminate\Http\Request;
use App\Models\UserBuyerLikes;
use Illuminate\Support\Str as Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Cache;
use App\Notifications\SendNotification;
use App\Mail\VerifiedMail;
use App\Mail\NewUserRegisterMail;
use App\Models\Token;


if (!function_exists('convertToFloat')) {
	function convertToFloat($value)
	{
		if (is_numeric($value)) {
			return number_format((float)$value, 2, '.', ' ');
		}
		return $value;
	}
}

if (!function_exists('convertToFloatNotRound')) {
	function convertToFloatNotRound($value)
	{
		if (is_numeric($value)) {
			$dec = 2;
			return number_format(floor($value * pow(10, $dec)) / pow(10, $dec), $dec);
		}
		return $value;
	}
}

if (!function_exists('uploadImage')) {
	/**
	 * Upload Image.
	 *
	 * @param array $input
	 *
	 * @return array $input
	 */
	function uploadImage($directory, $file, $folder, $type="profile", $fileType="jpg",$actionType="save",$uploadId=null,$orientation=null)
	{		
		$oldFile = null;
		if($actionType == "save"){
			$upload               		= new Uploads;
		}else{
			$upload               		= Uploads::find($uploadId);
			$oldFile = $upload->file_path;
		}
		$upload->file_path      	= $file->store($folder, 'public');
		$upload->extension      	= $file->getClientOriginalExtension();
		$upload->original_file_name = $file->getClientOriginalName();
		$upload->type 				= $type;
		$upload->file_type 			= $fileType;
		$upload->orientation 		= $orientation;
		$response             		= $directory->uploads()->save($upload);
		// delete old file
		if($oldFile){
			Storage::disk('public')->delete($oldFile);
		}
		return $upload;
	}
}

if (!function_exists('deleteFile')) {
	/**
	 * Destroy Old Image.	 *
	 * @param int $id
	 */
	function deleteFile($upload_id)
	{
		$upload = Uploads::find($upload_id);
		Storage::disk('public')->delete($upload->file_path);
		$upload->delete();
		return true;
	}
}


if (!function_exists('sendEmail')) {
	/**
	 * [sendEmail description]
	 * @return [type]        [description]
	 */
	function sendEmail()
	{			
		return true;
	}
}


if (!function_exists('sendResetPasswordEmail')) {
	function sendResetPasswordEmail($user){
		// $token = Str::random(64);

        // DB::table('password_resets')->insert(['email' => $user->email, 'token' => $token, 'created_at' =>  \Carbon\Carbon::now()->toDateTimeString()]);

        // $subject = trans('panel.email_contents.set_password.subject');
        // Mail::to($user->email)->send(new SetPasswordMail($user, $token, $subject)); 
        // return true;
	}
}


if (!function_exists('CurlPostRequest')) {
	function CurlPostRequest($url,$headers,$postFields)
 	{
 		$curl = curl_init();
	    curl_setopt_array($curl, array(
	           CURLOPT_URL => $url,
	           CURLOPT_RETURNTRANSFER => true,
	           CURLOPT_ENCODING => '',
	           CURLOPT_MAXREDIRS => 10,
	           CURLOPT_TIMEOUT => 0,
	           CURLOPT_FOLLOWLOCATION => true,
	           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	           CURLOPT_CUSTOMREQUEST => 'POST',
	           CURLOPT_POSTFIELDS => $postFields,
	           CURLOPT_HTTPHEADER => $headers,
	    ));
	    $response = curl_exec($curl);
	    curl_close($curl);
	    return json_decode($response);
	}
}

if (!function_exists('CurlGetRequest')) {
	function CurlGetRequest($url,$headers)
	{  
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => $headers
		));

		$response = curl_exec($curl);
		curl_close($curl);
		return json_decode($response);

	}
}

if (!function_exists('getCommonValidationRuleMsgs')) {
	function getCommonValidationRuleMsgs()
	{
		return [
			'password.required' => 'The new password is required.',
			'password.min' => 'The new password must be at least 8 characters',
			'password.different' => 'The new password and current password must be different.',
			'password.confirmed' => 'The password confirmation does not match.',
			'password_confirmation.required' => 'The new password confirmation is required.',
			'password_confirmation.min' => 'The new password confirmation must be at least 8 characters',
			'email.required' => 'Please enter email address.',
			'email.email' => 'Email is not valid. Enter email address for example test@gmail.com',
		];
	}
}

if (!function_exists('generateRandomString')) {
	function generateRandomString($length = 20) {
		
		$randomString = Str::random($length); 

		return $randomString;
	}
}

if (!function_exists('convertDateTimeFormat')) {
	function convertDateTimeFormat($value,$type='date')
	{
		$changeFormatValue = Carbon::parse($value);
		if ($type == 'date') {
			return $changeFormatValue->format(config('constants.date_format'));
		}else if($type == 'datetime'){
			return $changeFormatValue->format(config('constants.datetime_format'));
		}else if($type == 'time'){
			return $changeFormatValue->format(config('constants.time_format'));
		}
		return $changeFormatValue;
	}
}

if (!function_exists('totalLikes')) {
	function totalLikes($buyerId)
	{
		$total_likes = UserBuyerLikes::where('buyer_id',$buyerId)->where('liked',1)->count();
		return $total_likes;
	}
}

if (!function_exists('totalUnlikes')) {
	function totalUnlikes($buyerId)
	{
		$total_likes = UserBuyerLikes::where('buyer_id',$buyerId)->where('disliked',1)->count();
		return $total_likes;
	}
}

if (!function_exists('generateOTP')) {
	function generateOTP() {
		return rand(1000, 9999);
	}
}

if (!function_exists('getSetting')) {
	function getSetting($key)
	{
		$result = null;
		$setting = Setting::where('key',$key)->where('status',1)->first();
		if($setting){
			if($setting->type == 'image'){
				$result = $setting->image_url;
			}elseif($setting->type == 'video'){
				$result = $setting->video_url;
			}else{
				$result = $setting->value;
			}
		}
		return $result;
	}
}

if (!function_exists('getSettingDisplayName')) {
	function getSettingDisplayName($key)
	{
		$result = null;
		$setting = Setting::where('key',$key)->where('status',1)->first();
		if($setting){
			if($setting->type == 'image'){
				$result = $setting->display_name;
			}elseif($setting->type == 'video'){
				$result = $setting->display_name;
			}else{
				$result = $setting->display_name;
			}
		}
		
		return $result;
	}
}

if (!function_exists('getSettingDetail')) {
	function getSettingDetail($key)
	{
		$setting = Setting::where('key',$key)->where('status',1)->first();
		return $setting;
	}
}

if (!function_exists('getSettingGroupDetail')) {
	function getSettingGroupDetail($group)
	{
		$setting = Setting::where('group',$group)->where('status',1)->exists();
		return $setting;
	}
}

if (!function_exists('getUserSetting')) {
	function getUserSetting($key, $loginAsBuyer)
	{
		$result = null;
		$authUser = auth()->user();

		$userType = "seller";
		if($loginAsBuyer && $authUser->is_seller){
			$userType = "buyer";
		} else if($authUser->is_buyer){
			$userType = "buyer";
		}

		$setting = Setting::whereGroup('api')->where('key', $key)->whereUserId($authUser->id)->whereUserType($userType)->whereStatus(1)->first();
		if($setting){
			$result = $setting->value;
		}
		return $result;
	}
}

if (!function_exists('getUserNotificationSetting')) {
	function getUserNotificationSetting($key, $loginAsBuyer)
	{
		$result = null;
		$authUser = auth()->user();

		$userType = "seller";
		if($loginAsBuyer && $authUser->is_seller){
			$userType = "buyer";
		} else if($authUser->is_buyer){
			$userType = "buyer";
		}

		$setting = NotificationSetting::where('key', $key)->whereUserId($authUser->id)->whereUserType($userType)->whereStatus(1)->first();
		if($setting){
			$result = $setting;
		}
		return $result;
	}
}

if (!function_exists('SendPushNotification')) {
	function SendPushNotification($userId, $title, $message)
	{
		$fcmTokens = [];

		$configJsonFile = config('constants.firebase_json_file');
		
		$firebase = (new Factory)->withServiceAccount($configJsonFile);
		$messaging = $firebase->createMessaging();

		// Define the FCM tokens you want to send the message to
		$fcmTokens = User::where('id', $userId)->whereNotNull('device_token')->pluck('device_token')->toArray();

		// Create the notification
		$notification = Notification::create()
			->withTitle($title)
			->withBody($message);

		// Create the message
		$messageData = CloudMessage::new()->withNotification($notification); // Optional: Add custom data

		// Send the message to the FCM tokens
		try {
			$messaging->sendMulticast($messageData, $fcmTokens);
			// \Log::info('Push Notification Sent Successfully!');
		} catch (\Kreait\Firebase\Exception\MessagingException $e) {
		  //  \Log::info('Error sending firebase message:', ['MessagingException' => $e->getMessage()]);

		} catch (\Kreait\Firebase\Exception\FirebaseException $e) {
		
			//\Log::info('Firebase error:', ['FirebaseException' => $e->getMessage()]);
		}
	}
}


if (!function_exists('isPhoneNumberVerified')) {
	function isPhoneNumberVerified($countryCode, $phoneNumber)
	{
		$fullPhoneNumber = $countryCode.$phoneNumber;
		if (!Cache::get('otp_verified_' . $fullPhoneNumber)) {
			return false;
		}
		return true;
	}
}

if (!function_exists('forgetOtpCache')) {
	function forgetOtpCache($countryCode, $phoneNumber)
	{
		$fullPhoneNumber = $countryCode.$phoneNumber;
		Cache::forget('otp_' . $fullPhoneNumber);
		Cache::forget('otp_verified_' . $fullPhoneNumber);

		return true;
	}
}

if (!function_exists('sendNotificationToAdmin')) {

	function sendNotificationToAdmin($user, $type){

		//Start Send Notification to admin
		$adminUser = User::whereHas('roles', function($query){
			$query->where('id',config('constants.roles.super_admin'));
		})->first();

		$notificationData = [];
		if($type == 'new_user_register'){

			$notificationData = [
				'title'     => trans('notification_messages.new_user_register.title'),
				'message'   => trans('notification_messages.new_user_register.message'),
				'user_id'   => $user->id,
				'type'      => 'new_user_register',
				'notification_type' => 'new_user_register'
			];

			$subject = $notificationData['title'];
			Mail::to($adminUser->email)->queue(new NewUserRegisterMail($subject, $adminUser->name, $user));

		}else if($type == 'email_verified'){

			$roleName = $user->roles()->first()->title;
			$notificationData = [
                'title'     => trans('notification_messages.user_email_verified.title',['role'=>$roleName,'userName'=>$user->name]),
                'message'   => trans('notification_messages.user_email_verified.message'),
                'user_id'   => $user->id,
                'type'      => 'email_verified',
                'notification_type' => 'email_verified'
            ];

			$subject = $notificationData['title'];
			Mail::to($adminUser->email)->queue(new VerifiedMail($subject, $adminUser->name, $user));

		}
		
		if(count($notificationData) > 0 ){
			$adminUser->notify(new SendNotification($notificationData));
		}
		
		//End Send Notification to admin

		return true;
	}

}

if (!function_exists('sendNotificationToUser')) {

	function sendNotificationToUser($toUser, $user, $type,$notificationType){

		//Start Send Notification to admin
		$notificationData = [];
		if($type == 'email_verified'){
			$roleName = $user->roles()->first()->title;
			$notificationData = [
                'title'     => trans('notification_messages.user_email_verified.title',['role'=>$roleName,'userName'=>$user->name]),
                'message'   => trans('notification_messages.user_email_verified.message'),
                'user_id'   => $user->id,
                'type'      => $type,
                'notification_type' => $notificationType
            ];

			$subject = $notificationData['title'];
			Mail::to($toUser->email)->queue(new VerifiedMail($subject, $toUser->name, $user));
			
		}else if($type == 'new_user_register'){
			$notificationData = [
				'title'     => trans('notification_messages.new_user_register.title'),
				'message'   => trans('notification_messages.new_user_register.message'),
				'user_id'   => $user->id,
				'type'      => $type,
				'notification_type' => $notificationType
			];

			$subject = $notificationData['title'];
			Mail::to($toUser->email)->queue(new NewUserRegisterMail($subject, $toUser->name, $user));
		}
		
		if(count($notificationData) > 0 ){
			$toUser->notify(new SendNotification($notificationData));
		}
		
		//End Send Notification to admin

		return true;
	}

}

if (!function_exists('formatDateLabel')) {
    function formatDateLabel($createdAt)
    {
        $date = Carbon::parse($createdAt);
        $now = Carbon::now();        
        
        if ($date->isToday()) {
            return 'Today'; 
        }

        if ($date->isYesterday()) {
            return 'Yesterday';
        }
        
        if ($date->greaterThanOrEqualTo($now->subDays(6))) {
            return $date->format('l'); 
        }

        // If the date is older than a week, return the date in 'd-m-Y' format
        return $date->format('d-m-Y');
    }
}
