<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Mail\DealMail;

class SendNotification extends Notification
{
    use Queueable;

    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->data['task_type'] = 'cron';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if($notifiable->notificationSetting){
            if($notifiable->notificationSetting->email_notification){
                return ['database', 'mail'];
            }
        }
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject  = $this->data['title'];
        $userName = $notifiable->name;
        $message  = $this->data['message'];

        if( isset($this->data['notification_type']) && in_array($this->data['notification_type'], array('deal_notification')) ){

            return (new DealMail($subject, $userName, $message))->to($notifiable->email);

        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title'   => $this->data['title'],
            'message' => $this->data['message'],
        ];
    }
}
