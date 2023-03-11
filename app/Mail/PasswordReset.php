<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public $sendMail;
    public $newPassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sendMail, $newPassword)
    {
        $this->sendMail = $sendMail;
        $this->newPassword = $newPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'))->subject("Password Reset")->markdown('emails.password_reset');
    }
}
