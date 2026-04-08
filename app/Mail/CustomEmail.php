<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $user;

    public function __construct($token, $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('emails.forgot_password')
                    ->with([
                        'token' => $this->token,
                        'email' => $this->user->email,
                    ])
                    ->subject('Password Reset Request');
    }
}
