<?php

namespace App\Http\Controllers\Api\User;


use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification as NotificationModel;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{

    public function index(Request $request){

        try{
            $notificationsTypes = ['deal_notification','new_buyer_notification','new_message_notification','interested_buyer_notification'];

            $records = NotificationModel::whereIn('notification_type',$notificationsTypes)->whereNull('read_at')->orderBy('created_at','desc')->limit(5)->get();   

         
            $notificationRecords['deal_notification'] = [];
            $notificationRecords['new_buyer_notification'] = [];
            $notificationRecords['new_message_notification'] = [];
            $notificationRecords['interested_buyer_notification'] = [];

            
            if($records->count() > 0){
                foreach($records as $record){                    
                    $notificationRecords[$record->data['notification_type']][] = $record->data;
                }
            }

            //Return Success Response
            $responseData = [
                'status' => true,
                'notifications'   => $notificationRecords
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