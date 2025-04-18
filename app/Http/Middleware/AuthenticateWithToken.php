<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateWithToken
{
    public function handle(Request $request, Closure $next)
    {
        // Obtener el token JWT de la cookie
        $token = $request->cookie('auth_token');

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }

        try {
            // Establecer el token a JWTAuth
            JWTAuth::setToken($token);

            // Obtener el usuario asociado con el token
            $user = JWTAuth::toUser();

            // Si no se encuentra un usuario válido, devolver error
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 401);
            }

            // Agregar el usuario al request para acceder a él en controladores
            $request->merge(['user' => $user]);

        } catch (JWTException $e) {
            return response()->json(['error' => 'Token no válido'], 401);
        }

        return $next($request);
    }
}
