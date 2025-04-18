<?php
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AerolineaController;
use App\Http\Controllers\FacturaController;
//
Route::fallback(function (Request $request) {
    return response()->json([
        'message' => 'Esta ruta no existe'
    ], 404);
});




// Ruta para descargar el PDF de la factura
Route::get('/factura/{id}/download', [FacturaController::class, 'download']);
// Rutas públicas
Route::post('register', [AuthController::class, 'register']);


Route::get('aerolineas',[AerolineaController::class,'all']); //Obtener todas las aerolineas del servidor
Route::get('aerolineas/filtro',[AerolineaController::class,'buscar']); //Obtener aerolineas con filtros



// Rutas protegidas con JWT
Route::middleware('jwtAuth')->group(function () {
    Route::get('user', function (Request $request) {
        try {
            // Obtiene el usuario autenticado
            $user = JWTAuth::parseToken()->authenticate();
    
            // Devuelve los datos del usuario en formato JSON
            return response()->json([
                'user' => $user
            ], 200);
    
        } catch (\Exception $e) {
            // Si el usuario no está autenticado o hay un error
            return response()->json(['error' => 'No autorizado'], 401);
        }
    });
    Route::post('logout', [AuthController::class, 'logout']); //Cerrar sesión
    Route::post('refresh', [AuthController::class, 'refresh']); //Refrescar token

});
