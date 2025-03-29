<?php
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/send-email', function () {
    $details = [
        'subject' => 'Prueba de correo desde SES',
        'body' => 'Este es un mensaje de prueba enviado desde SES con Laravel',
    ];

    Mail::to('juancarrasquer@gmail.com')
        ->send(new \App\Mail\YourMailable($details));

    return 'Correo enviado!';
});
