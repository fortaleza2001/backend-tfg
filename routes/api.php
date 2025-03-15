<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

Route::fallback(function (Request $request) {
    return response()->json([
        'message' => 'Esta ruta no existe'
    ], 404);
});



// Rutas públicas
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);



// Rutas protegidas con JWT
Route::middleware('jwtAuth')->group(function () {
    Route::get('me', [AuthController::class, 'me']); // Obtener usuario autenticado
    Route::post('logout', [AuthController::class, 'logout']); // Cerrar sesión
    Route::post('refresh', [AuthController::class, 'refresh']); // Refrescar token

    // Otras rutas protegidas
    Route::get('profile', function () {
        return response()->json([
            'user' => auth()->user(),
            'message' => 'Perfil de usuario'
        ]);
    });
});
