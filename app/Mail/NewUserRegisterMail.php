<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewUserRegisterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject, $name,$user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$name,$user)
    {
        $this->subject = $subject;
        $this->name = ucwords($name);
        $this->user = $user;
    }

   /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.new-user-register', [
            'logoUrl' => asset(config('constants.default.admin_logo')),
            'name'    => $this->name,
            'user'    => $this->user,
        ])->subject($this->subject);
    }
}
