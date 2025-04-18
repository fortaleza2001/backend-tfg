<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|',
            'password' => 'required|string|',
        ]);

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'El usuario ya existe con ese correo.'
            ], 409);
        }


        $user = User::create([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = JWTAuth::fromUser($user);

        $cookie = Cookie::make('auth_token', $token, 180, '/', 'localhost', false, true);
    
        return response()->json(['message' => 'Usuario creado y Token guardado exitosamente'], 201)->withCookie($cookie);

        
    }

    // Inicio de sesión

    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }
    
        // Crear la cookie HttpOnly con el token
        $cookie = Cookie::make('auth_token', $token, 180, '/', 'localhost', false, true);
    
        return response()->json(['message' => 'Token guardado exitosamente'], 200)->withCookie($cookie);
    }
    
    public function checkAuth(Request $request)
{
    // Verificar si la cookie 'auth_token' existe
    $token = $request->cookie('auth_token');

    if (!$token) {
        return response()->json(['error' => 'No autenticado: No se encontró el token'], 401);
    }

    try {
        // Intentar verificar y decodificar el token con JWTAuth
        JWTAuth::setToken($token);
        $user = JWTAuth::toUser(); // Obtiene el usuario del token
    } catch (TokenExpiredException $e) {
        return response()->json(['error' => 'Token expirado'], 401);
    } catch (TokenInvalidException $e) {
        return response()->json(['error' => 'Token inválido'], 401);
    } catch (JWTException $e) {
        return response()->json(['error' => 'Error al procesar el token'], 401);
    }

    // Si la autenticación es exitosa, retorna los datos del usuario
    return response()->json(['message' => 'Usuario autenticado', 'user' => $user], 200);
}

    

  
    // Cerrar sesión
    public function logout(Request $request)
{
    // Verificar si la cookie 'auth_token' existe
    $token = $request->cookie('auth_token');

    if (!$token) {
        // Si no existe la cookie, respondemos que no hay sesión activa
        return response()->json(['message' => 'No se encontró sesión activa'], 400);
    }

    // Si la cookie existe, intentamos invalidar el token
    try {
        // Esto debería invalidar el token, opcional si usas una lista negra de tokens
        JWTAuth::setToken($token);
        JWTAuth::invalidate();  // Invalida el token
    } catch (JWTException $e) {
        return response()->json(['error' => 'Error al invalidar el token'], 500);
    }

    // Ahora eliminamos la cookie 'auth_token' enviando una cookie vacía
    Cookie::queue(Cookie::forget('auth_token'));

    // Responder que la sesión se cerró correctamente
    return response()->json(['message' => 'Sesión cerrada correctamente']);
}

    

    // Refrescar token
    public function refresh()
    {
        return response()->json([
            'token' => auth()->refresh()
        ]);
    }
}
