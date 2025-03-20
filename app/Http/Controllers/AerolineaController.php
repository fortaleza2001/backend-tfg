<?php

namespace App\Http\Controllers;

use App\Models\Aerolinea;
use Illuminate\Http\Request;

class AerolineaController extends Controller
{
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

    public function aerolineaUsuario()
    {
        $usuario = auth()->user();
    
        if (!$usuario) {
            return response()->json([
                'mensaje' => 'Usuario no autenticado',
                'contenido' => null
            ], 401);
        }
    
        $aerolinea = $usuario->aerolinea;
    
        if (!$aerolinea) {
            return response()->json([
                'mensaje' => 'El usuario no tiene una aerolínea asociada',
                'contenido' => null
            ], 404);
        }
    
        return response()->json([
            'mensaje' => 'Consulta exitosa',
            'contenido' => $aerolinea
        ], 200);
    }
    


}
