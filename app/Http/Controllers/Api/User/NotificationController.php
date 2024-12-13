<?php

namespace App\Http\Controllers\Api\User;


use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{

    public function index(Request $request){

        try{
            $notificationsTypes = ['deal_notification','dm_notification','new_buyer_notification','interested_buyer_notification'];

            $authUser = auth()->user();

            $authUserRoleId = $authUser->roles()->first()->id;

            $latestNotifications = $authUser->notification()
            ->whereIn('notification_type', $notificationsTypes)
            ->where('role_id',$authUserRoleId)
            // ->whereNull('read_at')
            ->orderBy('notification_type')
            ->orderBy('created_at', 'desc') 
            ->get()
            ->groupBy('notification_type') 
            ->map(function ($group) {
                return $group->take(5);
            });

            $notificationRecords['deal_notification'] = [];
            $notificationRecords['dm_notification'] = [];
            $notificationRecords['new_buyer_notification'] = [];
            $notificationRecords['interested_buyer_notification'] = [];

            if($latestNotifications->count() > 0){
                foreach($latestNotifications as $notificationType=>$records){
                    $notificationRecords[$notificationType] = [
                        'total' => $authUser->notification()->where('notification_type', $notificationType)->whereNull('read_at')->count(), 
                        'records' => []
                    ];

                    foreach($records as $indexKey=>$record){
                        $notificationRecords[$notificationType]['records'][$indexKey] = [
                            'data' => $record->data,
                            'read_at' => $record->read_at ? convertDateTimeFormat($record->read_at, 'datetime') : null,
                            'created_at' => $record->created_at ? formatDateLabel($record->created_at) : null,
                            'created_time' => $record->created_at ? convertDateTimeFormat($record->created_at, 'time') : null,
                        ];
                    }  
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

    public function markAsRead($type){
        try{
            $notificationsTypes = ['deal_notification','dm_notification','new_buyer_notification','new_message_notification','interested_buyer_notification'];

            if( !in_array($type,$notificationsTypes) ){
                $responseData = [
                    'status'        => false,
                    'error'         => 'Invalid Notification Type',
                ];
                return response()->json($responseData, 400);
            }

            $authUser = auth()->user();
            $authUser->notification()->where('notification_type',$type)->update(['read_at' => now()]);

            //Return Success Response
            $responseData = [
                'status'  => true,
                'message' => 'Mark as read successfully!'
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