<?php

namespace App\Listeners;

use App\Mail\DealMail;
use App\Mail\ChatMessageMail;
use App\Events\NotificationSent;
use Illuminate\Support\Facades\Mail;

class SendNotificationEmail
{
    public function handle(NotificationSent $event)
    {
        $user = $event->user;
        $notificationData = $event->notificationData;

        if (isset($user->notificationSetting) && $user->notificationSetting->email_notification) {
            $subject  = $notificationData['title'];
            $message  = $notificationData['message'];

            if(isset($notificationData['notification_type']) && $notificationData['notification_type'] == 'dm_notification'){
                Mail::to($user->email)->queue(new ChatMessageMail($subject, $user->name, $message));
            }

            if(isset($notificationData['notification_type']) && $notificationData['notification_type'] == 'deal_notification'){
                Mail::to($user->email)->queue(new DealMail($subject, $user->name, $message));
            }

        }

    }
}
