<?php

namespace App\Mail;

use App\Models\UserOTP;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $otp, $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otp, $user)
    {
        $this->otp = $otp;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@MuskoApp.com', 'Musko App')
                    ->view('emailku')
                    ->subject('Noreply OTP Code Verification')
                    ->with([
                        "otp" => $this->otp,
                        "user" => $this->user,
                    ]);
    }
}
