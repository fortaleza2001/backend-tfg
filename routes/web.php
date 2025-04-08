<?php
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cookie;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/check-auth', [AuthController::class, 'checkAuth']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::post('/login', [AuthController::class, 'login']);
    

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

    }
    else
    {
        $token = JWTAuth::fromUser($userReal);
       
        $cookie = Cookie::make('auth_token', $token, 60, '/', 'localhost', false, true);
       
    
        return redirect(env("TARGET_DOMAIN")."/home")->cookie($cookie);
    }
 
});
