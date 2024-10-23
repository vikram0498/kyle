<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject,$invitationLink,$reminderNo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$invitationLink,$reminderNo)
    {
        $this->subject = $subject;
        $this->invitationLink = $invitationLink;
        $this->reminderNo = $reminderNo;
    }

   /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	//invitation-reminder
        return $this->markdown('emails.invitation-reminder', [
            'reminderNo' => $this->reminderNo,
            'invitationLink' => $this->invitationLink,
        ])->subject($this->subject)
	->withSwiftMessage(function ($message) {
	    $headers = $message->getHeaders();
   	    $headers->removeAll('Content-Type');
   	    $headers->addTextHeader('Content-Type', 'text/html; charset=utf-8');
   		 \Log::info($headers->toString()); // Log headers to verify
	});

    }
}
