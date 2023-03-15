<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $userEmail;
    public $query;
    public $subject;
    public function __construct($userEmail, $subject, $query)
    {
        $this->userEmail = $userEmail;
        $this->subject = $subject;
        $this->query = $query;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->userEmail)->subject("Contact Us LF Exam")->markdown('emails.contact_us');
    }
}
