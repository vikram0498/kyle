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
            $records = NotificationModel::orderBy('created_at','desc')->limit(5)->get();   

            $notificationRecords = [];

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