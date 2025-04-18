<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmarAerolineaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $aerolinea;

    public function __construct($aerolinea)
    {
        $this->aerolinea = $aerolinea;
    }

    public function build()
    {
        return $this->subject('Confirmación para creación de aerolínea')
            ->view('emails.confirmar-aerolinea');
    }
}
