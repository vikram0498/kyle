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

    public function index(Request $request,$type){

        try{
            $perPage = $request->per_page ?? 10;
            $records = NotificationModel::paginate($perPage);   

            //Return Success Response
            $responseData = [
                'status' => true,
                'notifications'   => $records
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