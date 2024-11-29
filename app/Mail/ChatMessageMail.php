<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChatMessageMail extends Mailable
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
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function build()
    {
        return $this->markdown('emails.chat-message-mail', [
            'logoUrl' => asset(config('constants.default.admin_logo')),
            'name'    => $this->name,
            'message' => $this->message,
        ])->subject($this->subject);
    }
}
