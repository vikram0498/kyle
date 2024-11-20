<?php
namespace App\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\Notification;

class DatabaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->data;

        $data['notification_id'] = $notification->id;

        /* Send the immidate notification */
        $user_id = $notifiable->id;
        $user = auth()->user();

        if($notifiable->notificationSetting){
            if($notifiable->notificationSetting->push_notification){

                if (auth()->check()) {
                    // \Log::info('Start To Send Push Notification-'.$user_id);
        
                    SendPushNotification($user_id, $data['title'], $data['message']);
        
                    // \Log::info('End To Send Push Notification');
                    
                } else if(isset($data['task_type']) && $data['task_type'] == 'cron'){
            
                    // \Log::info('Start To Send Push Notification-'.$user_id);
        
                    SendPushNotification($user_id, $data['title'], $data['message']);
                    
                    // \Log::info('End To Send Push Notification');
                }
               
            }
        }
       
        /* From this Save the value from database */
        return $notifiable->routeNotificationFor('database')->create([
            'id'                => $notification->id,
            'type'              => get_class($notification),
            'data'              => $data,
            'notification_type' => $data['notification_type'],
            'read_at'           => null,
        ]);
    }

}
