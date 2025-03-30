<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    // Inicio de sesión
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        // Intentar autenticar con las credenciales proporcionadas
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }
    
        // Si el token es generado, devolvemos el token en una cookie
        return response()->json(['message' => 'Login exitoso'])
    ->cookie(
        'auth-token', // Nombre de la cookie
        $token, // Valor del token
        60, // Duración de la cookie en minutos
        '/', // Ruta a la que la cookie será accesible
        env('TARGET_DOMAIN'), // Dominio, puede ser null si es el dominio actual
        false, // Secure: establece false si no estás utilizando HTTPS
        true, // HttpOnly: Hace que la cookie no sea accesible desde JavaScript
        false, // SameSite: Puede ser 'Strict', 'Lax', o 'None'
        false // Solo si deseas que la cookie sea un HttpOnly cookie adicional
    );

    }

  
    // Cerrar sesión
    public function logout()
    {
        auth()->logout();
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
