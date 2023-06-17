<?php

use Illuminate\Support\Str as Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Uploads;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
		}else if($type='datetime'){
			return $changeFormatValue->format(config('constants.datetime_format'));
		}
		return $changeFormatValue;
	}
}