<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\YourMailable;

class PruebaEnvioEmailTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_send_email()
    {
        // Fake el envío de correos
        Mail::fake();

        // Realiza la solicitud a la ruta '/send-email'
        $response = $this->get('/send-email');

        // Verifica que la respuesta sea la esperada
        $response->assertSee('Correo enviado!');

        // Verifica que el correo fue "enviado"
        Mail::assertSent(\App\Mail\YourMailable::class);
    }
 
}
