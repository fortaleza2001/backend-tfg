<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AerolineaController;
//
Route::fallback(function (Request $request) {
    return response()->json([
        'message' => 'Esta ruta no existe'
    ], 404);
});



// Rutas públicas
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('aerolineas',[AerolineaController::class,'all']); //Obtener todas las aerolineas del servidor
Route::get('aerolineas/filtro',[AerolineaController::class,'buscar']); //Obtener aerolineas con filtros
Route::get('aerolinea/usuario',[AerolineaController::class,'aerolineaUsuario']);//Obtener usuario de la aerolinea


// Rutas protegidas con JWT
Route::middleware('jwtAuth')->group(function () {
   
    Route::post('logout', [AuthController::class, 'logout']); //Cerrar sesión
    Route::post('refresh', [AuthController::class, 'refresh']); //Refrescar token

});
