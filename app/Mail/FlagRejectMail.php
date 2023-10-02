<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FlagRejectMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject,$name,$message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$name,$message)
    {
        $this->subject = $subject;
        $this->name = $name;
        $this->message = $message;
    }

   /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.flag-rejected-mail', [
                'name' => $this->name,
                'message' => $this->message,
            ])->subject($this->subject);
    }
}
