<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    public function __construct($token, $email)
    {
        $this->url = env("TARGET_DOMAIN") . "/CambiarContraseña?token=" . urlencode($token) . "&email=" . urlencode($email);
    }
    

    public function build()
    {
        return $this->subject('Restablece tu contraseña')
                    ->view('emails.password-reset')
                    ->with([
                        'url' => $this->url,
                    ]);
    }
}
