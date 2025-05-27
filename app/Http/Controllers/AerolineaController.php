<?php

namespace App\Http\Controllers;

use App\Models\Aerolinea;
use App\Models\datos_creador_aerolinea;
use App\Models\metodo_pago_aerolinea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmarAerolineaMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *     name="Aerolineas",
 *     description="Endpoints relacionados con aerolíneas"
 * )
 */
class AerolineaController extends Controller
{
/**
    * @OA\Get(
    *     path="/aerolineas",
    *     summary="Mostrar Aerolineas",
    *     tags={"Aerolineas"},  
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todas las aerolineas."
    *     )
    *  
    * )
*/

    public function all()
    {
        $aerolineas = Aerolinea::all();

        return response()->json([
            'mensaje' => 'Consulta exitosa',
            'contenido' => $aerolineas
        ], 200);
    }
    public function buscar(Request $request)
    {
        $query = Aerolinea::query();

        if ($request->has('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->has('pais')) {
            $query->where('pais', $request->pais);
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        $aerolineas = $query->get();

        return response()->json([
            'mensaje' => 'Búsqueda exitosa',
            'contenido' => $aerolineas
        ], 200);
    }

    public function enviarConfirmacion($aerolinea)
    {
    

        Mail::to('juancarrasquer@gmail.com')->send(new ConfirmarAerolineaMail($aerolinea));

    
    }

    public function aerolineasUsuario()
    {
        $usuario = auth()->user();
    
        if (!$usuario) {
            return response()->json([
                'mensaje' => 'Usuario no autenticado',
                'contenido' => null
            ], 401);
        }
    
        $aerolineas = $usuario->aerolineas;
    
        if (!$aerolineas) {
            return response()->json([
                'mensaje' => 'El usuario no tiene una aerolínea asociada',
                'contenido' => null
            ], 404);
        }
    
        return response()->json([
            'mensaje' => 'Consulta exitosa',
            'contenido' => $aerolineas
        ], 200);
    }
    

    public function obtener_aerolinea($id)
    {
        $usuario = auth()->user();
    
        if (!$usuario) {
            return response()->json([
                'mensaje' => 'Usuario no autenticado',
                'contenido' => null
            ], 401);
        }
    
        $aerolinea = Aerolinea::with(['datosCreador','administrador'])
        ->where('id', $id)
        ->first();
    

        if($aerolinea==null)
        {
            return response()->json([
                'mensaje' => 'La aerolinea con id ' . $id . "no existe",
                'contenido' => null
            ], 404);
        }

        if($aerolinea->administrador->id != $usuario->id)
        {
            return response()->json([
                'mensaje' => 'La aerolinea con id ' . $id . "no pertenece a este usuario",
                'contenido' => null
            ], 404);
        }

        
    
        return response()->json([
            'mensaje' => 'Consulta exitosa',
            'contenido' => $aerolinea
        ], 200);
    }

    public function CrearAerolinea(Request $request)
{
    $usuario = auth()->user();

    if (!$usuario) {
        return response()->json([
            'mensaje' => 'Usuario no autenticado',
            'contenido' => null
        ], 401);
    }

    $errores = [];

    // Validar campos requeridos
    if (empty($request->nombreAerolinea)) {
        $errores[] = 'El nombre de la aerolínea es obligatorio.';
    } else {
        $existe = Aerolinea::where('nombre', $request->nombreAerolinea)->exists();
        if ($existe) {
            $errores[] = 'Ya existe una aerolínea con ese nombre.';
        }
    }

    if (empty($request->codigoIATA)) {
        $errores[] = 'El código IATA es obligatorio.';
    } else {
        $existeCodigo = Aerolinea::where('codigo_AITA', $request->codigoIATA)->exists();
        if ($existeCodigo) {
            $errores[] = 'Ya existe una aerolínea con ese código IATA.';
        }
    }

    if (empty($request->direccionAerolinea)) {
        $errores[] = 'La dirección de la aerolínea es obligatoria.';
    }

    if (empty($request->paisOrigen)) {
        $errores[] = 'El país de origen es obligatorio.';
    }

    if (empty($request->nombreCreador)) {
        $errores[] = 'El nombre del creador de la  aerolínea es obligatorio.';
    } 

    if (empty($request->dniCreador)) {
        $errores[] = 'El DNI del creador de la  aerolínea es obligatorio.';
    } 

    if (empty($request->direccionCreador)) {
        $errores[] = 'La direccion del creador de la  aerolínea es obligatorio.';
    } 

    if (empty($request->fechaNacimientoCreador)) {
        $errores[] = 'La fecha de nacimiento del creador de la  aerolínea es obligatorio.';
    } 

    if (empty($request->numeroTelefono)) {
        $errores[] = 'El numero de telefono del creador de la  aerolínea es obligatorio.';
    } 

    
    if (empty($request->nombreTarjeta)) {
        $errores[] = 'El nombre de la tarjeta de la forma de pago de la  aerolínea es obligatorio.';
    } 
    if (empty($request->numeroTarjeta)) {
        $errores[] = 'El numero de la tarjeta de la forma de pago de la  aerolínea es obligatorio.';
    } 
    if (empty($request->fechaCaducidad)) {
        $errores[] = 'La fecha de caducidad  de la forma de pago de la  aerolínea es obligatorio.';
    } 
    if (empty($request->cvv)) {
        $errores[] = 'El numero de cvv de la forma de pago de la  aerolínea es obligatorio.';
    } 


    if (!empty($errores)) {
        return response()->json([
            'errors' => $errores
        ], 409);
    }

    $aerolinea = new Aerolinea();
    $aerolinea->nombre = $request->nombreAerolinea;
    $aerolinea->direccion = $request->direccionAerolinea;
    $aerolinea->codigo_AITA = $request->codigoIATA;
    $aerolinea->pais = $request->paisOrigen;
    $aerolinea->usuario_administrador = $usuario->id;

    if ($request->hasFile('logoAerolinea')) {
        $path = $request->file('logoAerolinea')->store('logos', 'public');
        $aerolinea->logoAerolinea = $path;
    }

    $datos_pago = new metodo_pago_aerolinea();

    $datos_pago->nombre_titular = $request->nombreTarjeta ;
    $datos_pago->numero_tarjeta = $request->numeroTarjeta ;
    $datos_pago->fecha_caducidad = $request->fechaCaducidad ;
    $datos_pago->cvv = $request->cvv ;
    $datos_pago->save();

    $datos_creador = new datos_creador_aerolinea();
    $datos_creador->nombreCreador = $request->nombreCreador;
    $datos_creador->dniCreador = $request->dniCreador;
    $datos_creador->direccionCreador = $request->direccionCreador;
    $datos_creador->numeroTelefono = $request->numeroTelefono;
    $datos_creador->fechaNacimientoCreador = $request->fechaNacimientoCreador;
    $datos_creador->save();
    
    $aerolinea->id_datos_creador=$datos_creador->id;
    $aerolinea->id_datos_pago=$datos_pago->id;
    $token_confirmacion = Str::random(60);
    $aerolinea->token_confirmado = $token_confirmacion;
    $aerolinea->save();

    $this->enviarConfirmacion($aerolinea);

    return response()->json([
        'mensaje' => 'Aerolínea creada correctamente en proceso para su verificación',
    ], 201);
}

    
    


}
