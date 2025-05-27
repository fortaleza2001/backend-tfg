<?php
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\AerolineaController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\TicketpdfController;
use App\Http\Controllers\VueloController;

use App\Models\Aerolinea;


Route::get('/', function () {
    return view('emails.welcome');
});

use Illuminate\Support\Str;

Route::get('/aerolineas/confirmar/{id}/{token}', function($id, $token) {
    // Verifica si el token es válido y pertenece a esa aerolínea
    $aerolinea = Aerolinea::findOrFail($id);

    // Compara el token almacenado con el que llegó en el correo
    if ($token !== $aerolinea->token_confirmado) {
        return response()->json(['mensaje' => 'Token no válido o expirado'], 400);
    }

    $aerolinea->confirmado= true;
    $aerolinea->save();


    return "Aerolínea con ID $id confirmada.";
});

Route::get('/aerolineas/rechazar/{id}/{token}', function($id, $token) {
    // Encuentra la aerolínea por ID
    $aerolinea = Aerolinea::findOrFail($id);

    // Verifica si el token es válido
    if ($token !== $aerolinea->token_confirmado) {
        return response()->json(['mensaje' => 'Token no válido o expirado'], 400);
    }



    return "Aerolínea con ID $id rechazada.";
});

Route::get('/guardar', [PayPalController::class, 'setupCard']);
Route::get('/prueba-correo', [AerolineaController::class, 'enviarConfirmacion']);
Route::get('/check-auth', [AuthController::class, 'checkAuth']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/registro', [AuthController::class, 'register']);
Route::get('/aerolineas', [AerolineaController::class, 'all']);
Route::post('/buscar-vuelos', [VueloController::class, 'buscarVuelos']);
Route::get('/obtener-paises', [AirportController::class, 'obtenerPaises']);
Route::get('/obtener-aeropuertos/{pais}', [AirportController::class, 'obtenerAeropuertosPais']);
Route::get('/obtener-aerolineas-trabajo', [AerolineaController::class, 'all']);
Route::get('/obtener-vuelo/{id}', [VueloController::class, 'obtenerVuelo']);

Route::get('/buy-flight', [PayPalController::class, 'buyFlight'])->name('paypal.buy');
Route::get('/paypal-success', [PayPalController::class, 'success'])->name('paypal.success');
Route::get('/paypal-cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
Route::get('/aeropuertos/{countryCode}', [AirportController::class, 'byCountry']);
Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail']);
Route::post('verificar-token-pass', [AuthController::class, 'verificarTokencontrasena']);
Route::post('cambiar-contrasena', [AuthController::class, 'cambiarcontrasena']);
Route::post('/soporte/mensaje', [AuthController::class, 'enviarMensaje']);
Route::middleware('auth.token')->group(function () {
    Route::get('aerolinea/usuario',[AerolineaController::class,'aerolineasUsuario']);
    Route::post('aerolinea/CrearAerolinea',[AerolineaController::class,'CrearAerolinea']);
    Route::post('aerolinea/CrearVuelo',[VueloController::class,'store']);
    Route::get('aerolinea/{id}',[AerolineaController::class,'obtener_aerolinea']);
    Route::get('aerolinea/{id}/vuelos',[VueloController::class,'obtenerVuelos']);
    Route::post('vuelos/comprarVuelo',[TicketController::class,'comprarTickets']);
    Route::get('/reservas/vuelos', [TicketController::class, 'obtenerVuelos']);
    Route::get('/ticket/{id}', [TicketpdfController::class, 'generarTicket']);
    Route::get('/generar-factura/{id}', [TicketpdfController::class, 'generarFactura']);
    Route::get('/obtenerReservas/{id}', [VueloController::class, 'obtenerVueloReserva']);
    Route::post('/devolverTicket/{id}', [TicketController::class, 'devolverTicket']);
 
    
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

Route::get('/auth/github', function () {
    return Socialite::driver('github')->with(["prompt" => "select_account"]) ->redirect();
});

Route::get('/auth/github/callback', function () {
    $user = Socialite::driver('github')->stateless()->user();

    // Buscar si ya existe un usuario con ese email
    $userReal = User::where('email', $user->getEmail())->first();

    if (!$userReal) 
    {
        $userReal2 = User::create([
                    'email' => $user->email,
                    'password' => '1234'
                ]);

                $token = JWTAuth::fromUser($userReal2);
                 $cookie = Cookie::make('auth_token', $token, 600, '/', env('COOKY_DOMAIN'), false, true);
                 return redirect(env("TARGET_DOMAIN")."/home")->cookie($cookie);
    }
    else
    {
        $token = JWTAuth::fromUser($userReal);
       
        $cookie = Cookie::make('auth_token', $token, 600, '/', env('COOKY_DOMAIN'), false, true);
       
    
        return redirect(env("TARGET_DOMAIN")."/home")->cookie($cookie);
    }
 
});

Route::get('auth/google', function () {
    return Socialite::driver('google')->with(["prompt" => "select_account"]) ->redirect();
});
// magia 5
Route::get('auth/google/callback', function () {

    $user = Socialite::driver('google')->stateless()->user();

    $userReal = User::where('email', $user->getEmail())->first();

    if (!$userReal) 
    {
         $userReal2 = User::create([
                    'email' => $user->email,
                    'password' => '1234'
                ]);

                $token = JWTAuth::fromUser($userReal2);
                 $cookie = Cookie::make('auth_token', $token, 600, '/', 'localhost', false, true);
                 return redirect(env("TARGET_DOMAIN")."/home")->cookie($cookie);
    
    }
    else
    {
        $token = JWTAuth::fromUser($userReal);
       
        $cookie = Cookie::make('auth_token', $token, 6600, '/', 'localhost', false, true);
       
    
        return redirect(env("TARGET_DOMAIN")."/home")->cookie($cookie);
    }
});


Route::get('auth/facebook', function () {
    return Socialite::driver('facebook')->with(['auth_type' => 'reauthenticate'])->scopes(['email'])->redirect();
});

Route::get('auth/facebook/callback', function () {
    $user = Socialite::driver('facebook')->stateless()->user();

    $userReal = User::where('email', $user->getEmail())->first();

    if (!$userReal) 
    {

    }
    else
    {
        $token = JWTAuth::fromUser($userReal);
       
        $cookie = Cookie::make('auth_token', $token, 60, '/', 'localhost', false, true);
       
    
        return redirect(env("TARGET_DOMAIN")."/home")->cookie($cookie);
    }
});

