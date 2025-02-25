<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

     public $emailData;

    public function __construct($user)
    {
        $this->emailData = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('benjamin.andoh@abibima.org')
                    ->subject($this->emailData['subject'])
                    ->view('Mail.verify_email')->with(['data' => $this->emailData]);
    }
}
