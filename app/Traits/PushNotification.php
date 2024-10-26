<?php
namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

trait PushNotification
{
    function firebaseNotification($fcmTokens, $title, $message) {
        $configJsonFile = storage_path('firebase/auth-config.json');
        
        $firebase = (new Factory)->withServiceAccount($configJsonFile);
        $messaging = $firebase->createMessaging();

        // Create the notification
        $notification = Notification::create()->withTitle($title)->withBody($message);

        // Create the message
        $messageData = CloudMessage::new()->withNotification($notification); // Optional: Add custom data

        // Send the message to the FCM tokens
        try {
            $messaging->sendMulticast($messageData, $fcmTokens);
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            Log::info('Error sending firebase message:'. $e->getMessage());
        } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
            Log::info('Firebase error:'. $e->getMessage());
        }
    }
}