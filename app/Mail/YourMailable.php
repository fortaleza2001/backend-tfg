<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class YourMailable extends Mailable
{
    public $details;

    // Constructor para pasar los detalles al correo
    public function __construct($details)
    {
        $this->details = $details;
    }

    // Definir la vista y los datos que se pasarán a la vista
    public function build()
    {
        return $this->view('emails.your_email_template')
                    ->with([
                        'subject' => $this->details['subject'],
                        'body' => $this->details['body'],
                    ])
                    ->subject($this->details['subject']);
    }
}
