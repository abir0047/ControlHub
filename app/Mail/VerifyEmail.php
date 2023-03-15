<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $sendMail;
    public $password;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sendMail, $password)
    {
        $this->sendMail = $sendMail;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'))->subject("Your new account password")->markdown("emails.verify_email");
    }
}
