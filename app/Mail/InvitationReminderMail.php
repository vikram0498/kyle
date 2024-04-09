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
        return $this->markdown('emails.invitation-remider', [
            'reminderNo' => $this->reminderNo,
            'invitationLink' => $this->invitationLink,
        ])->subject($this->subject);
    }
}
