<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DealMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject, $name,$message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$name,$message)
    {
        $this->subject = $subject;
        $this->name = ucwords($name);
        $this->message = $message;
    }

   /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.deal-mail', [
            'logoUrl' => asset(config('constants.default.admin_logo')),
            'name'    => $this->name,
            'message' => $this->message,
        ])->subject($this->subject);
    }
}
