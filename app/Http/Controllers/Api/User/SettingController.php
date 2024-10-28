<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    
    public function index(Request $request){
        try {
            $loginAsBuyer = $request->has('login_as_buyer') && $request->query('login_as_buyer') == 1 ? true : false;

            $settings = [];
            foreach(config('constants.user_settings') as $userSettingKey => $userSetting){
                $userSetting['key'] = $userSettingKey;
                $userSetting['value'] = getUserSetting($userSettingKey, $loginAsBuyer) ?? $userSetting['value'];

                $settings[] = $userSetting;
            }

            //Return Success Response
            $responseData = [
                'status'    => true,
                'data'     => $settings
            ];
            return response()->json($responseData, 200);
        } catch (\Throwable $th) {
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
		        'error_details' => $th->getMessage().'->'.$th->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }

    public function notificationSettings(Request $request){
        try {
            $loginAsBuyer = $request->has('login_as_buyer') && $request->query('login_as_buyer') == 1 ? true : false;

            $notificationSettings = [];
            foreach(config('constants.user_notification_settings') as $userSettingKey => $userSetting){
                $userSetting['key'] = $userSettingKey;
                $userSetting['value'] = getUserNotificationSetting($userSettingKey, $loginAsBuyer) ?? $userSetting['value'];

                $notificationSettings[] = $userSetting;
            }

            //Return Success Response
            $responseData = [
                'status'    => true,
                'data'     => $notificationSettings
            ];
            return response()->json($responseData, 200);
        } catch (\Throwable $th) {
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
		        'error_details' => $th->getMessage().'->'.$th->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }
}
