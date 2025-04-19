<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'tipo_de_billete' => 'required|string|max:255',
            'estado' => 'required|in:disponible,vendido',
            'vuelo_id' => 'required|exists:vuelos,id',
        ]);

        // Crear ticket
        $ticket = Ticket::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'tipo_de_billete' => $request->tipo_de_billete,
            'estado' => $request->estado,
            'vuelo_id' => $request->vuelo_id,
        ]);

        return response()->json([
            'mensaje' => 'Ticket creado correctamente',
            'ticket' => $ticket,
        ], 201);
    }
}
