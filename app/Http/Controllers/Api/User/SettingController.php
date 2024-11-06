<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    
    public function userSettings(Request $request){
        try {
            $authUser = auth()->user();
            $loginAsBuyer = $request->has('login_as_buyer') && $request->query('login_as_buyer') == 1 ? true : false;

            $userType = $this->getUserType($authUser, $loginAsBuyer);
            $settings = [];
            foreach(config('constants.user_settings') as $userSettingKey => $userSetting){
                if(in_array($userType, $userSetting['setting_for'])){
                    $userSetting['key'] = $userSettingKey;
                    $userSetting['value'] = getUserSetting($userSettingKey, $loginAsBuyer) ?? $userSetting['value'];
                    $settings[] = $userSetting;
                }
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

    public function updateUserSettings(Request $request){
        DB::beginTransaction();
        try {
            
            $this->saveUpdateSetting($request, "normal");

            DB::commit();
            //Return Success Response
            $responseData = [
                'status'    => true,
                'message'   => "settings updated successfully"
            ];
            return response()->json($responseData, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
		        'error_details' => $th->getMessage().'->'.$th->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }

    public function userNotificationSettings(Request $request){
        try {
            $authUser = auth()->user();
            $loginAsBuyer = $request->has('login_as_buyer') && $request->query('login_as_buyer') == 1 ? true : false;

            $userType = $this->getUserType($authUser, $loginAsBuyer);
            $notificationSettings = [];
            foreach(config('constants.user_notification_settings') as $userSettingKey => $userSetting){
                if(in_array($userType, $userSetting['setting_for'])){
                    $userSetting['key'] = $userSettingKey;
                    $setting = getUserNotificationSetting($userSettingKey, $loginAsBuyer) ?? null;
                    if($setting){
                        $userSetting['value'] = $setting->value == 'enable' ? true : false;
                        $userSetting['push']['enabled'] = $setting->push_notification ? true : false;
                        $userSetting['email']['enabled'] = $setting->email_notification ? true : false;
                    }else{
                         $userSetting['value'] = $userSetting['value'] == 'enable' ? true : false;
                    }
                   
                    $notificationSettings[] = $userSetting;
                }
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

    public function updateUserNotificationSettings(Request $request){
        DB::beginTransaction();
        try {
            $this->saveUpdateSetting($request, "notification");

            DB::commit();
            //Return Success Response
            $responseData = [
                'status'    => true,
                'message'   => "settings updated successfully"
            ];
            return response()->json($responseData, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
		        'error_details' => $th->getMessage().'->'.$th->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }

    public function getUserType($authUser, $loginAsBuyer=false){
        $userType = "seller";
        if($loginAsBuyer && $authUser->is_seller){
            $userType = "buyer";
        } else if($authUser->is_buyer){
            $userType = "buyer";
        }
        return $userType;
    }

    private function saveUpdateSetting($request, $settingType ="notification"){
        $authUser = auth()->user();
        $loginAsBuyer = $request->has('login_as_buyer') && $request->query('login_as_buyer') == 1 ? true : false;

        if($settingType == 'notification'){
            $userPredefindSettings = config('constants.user_notification_settings');
        } else {
            $userPredefindSettings = config('constants.user_settings');
        }
        $userPredefindSettingKeys = array_keys($userPredefindSettings);
        
        $userType = $this->getUserType($authUser, $loginAsBuyer);
        $userSettings = $request->only($userPredefindSettingKeys);

        foreach($userSettings as $userSettingKey => $userSettingValue){
            $settingData = $userPredefindSettings[$userSettingKey];
            if(in_array($userType, $settingData['setting_for'])){
                if($settingType == 'notification'){
                    $settingValue = getUserNotificationSetting($userSettingKey, $loginAsBuyer);
                } else {
                    $settingValue = getUserSetting($userSettingKey, $loginAsBuyer);
                }
                if(!is_null($settingValue)){
                    if($settingType == 'notification'){
                        $notificationRecord = [];
                        
                        if(isset($userSettingValue['push'])){
                            $notificationRecord['push_notification'] = $userSettingValue['push'] == true ? 1 : 0;
                        }elseif(isset($userSettingValue['email'])){
                            $notificationRecord['email_notification'] = $userSettingValue['email'] == true ? 1 : 0;
                        }
                        
                        if(count($notificationRecord) > 0){
                             NotificationSetting::whereUserId($authUser->id)->where('key', $userSettingKey)->whereUserType($userType)->whereStatus(1)->update($notificationRecord);
                        }
                       
                    } else {
                        Setting::whereUserId($authUser->id)->where('key', $userSettingKey)->whereUserType($userType)->whereStatus(1)->update(['value' => $userSettingValue]);
                    }
                } else {

                    $settingCreateData = [
                        'user_id'              => $authUser->id,
                        'key'                  => $userSettingKey,
                        'display_name'         => $settingData['display_name'],
                        'user_type'            => $userType,
                        'status'               => 1,
                    ];
                    
                    if(isset($userSettingValue['push'])){
                        $settingCreateData['push_notification'] = $userSettingValue['push'] == true ? 1 : 0;
                    }elseif(isset($userSettingValue['email'])){
                        $settingCreateData['email_notification'] = $userSettingValue['email'] == true ? 1 : 0;
                    }
                    
                    if($settingType == 'notification'){
                        $settingCreateData['value'] = $userSettingValue ? 'enable' : 'disable';                        
                        NotificationSetting::create($settingCreateData);
                    } else {
                        $settingCreateData['value'] = $userSettingValue;  
                        $settingCreateData['group'] = 'api';  
                        $settingCreateData['type'] = 'text';  
                        Setting::create($settingCreateData);
                    }
                }
            }
        }
    }
}
