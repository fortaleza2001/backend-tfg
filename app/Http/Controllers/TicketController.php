<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use App\Models\Vuelo;
use App\Models\Airport;
use Carbon\Carbon;
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

     public function devolverTicket(Request $request,$id)
    {
        $ticket = Ticket::find($id);
        
        $ticket->usuario_id = null;
        $ticket->direccion_usuario = null;
        $ticket->nombre_usuario = null;
        $ticket->dni_usuario = null;
        $ticket->ticket_pdf = null;
        $ticket->fecha_compra = null;
      
        $ticket->save(); 

        return response()->json([
        'contenido'=>'Ticket devuelto correctamente'
        ], 200);
    }

    

    public function comprarTickets(Request $request)
{
    // Obtener el usuario autenticado
    $usuario = auth()->user();

    // Obtener los tickets del request
    $billetes = $request->input('billetes');
    $tickets = $request->input('tickets');
    $vuelo = $request->input('vuelo');

    $billetesAcomprar = [];
    $procesados = [];

    foreach ($billetes as $billete) {
        $tipoBillete = $tickets[intval($billete['type_index'])];
    
        $billeteAcomprar = Ticket::whereNotIn('id', $procesados)
                                ->where('vuelo_id', $vuelo['id'])
                                 ->where('tipo', $tipoBillete['type'])
                                 ->where('estado', 'disponible')
                                 ->first();
    
        if($billeteAcomprar==null)
        {
            return response()->json(['error' => 'No se pudo completar la operación'], 400);
        }
        $procesados[] = $billeteAcomprar->id;
       
        $billetesAcomprar[] = [
            'tipo_billete' => $tipoBillete,
            'denominacion' => $billete,
            'billete' => $billeteAcomprar,
        ];
    }
    // Recorriendo el array $billetesAcomprar
foreach ($billetesAcomprar as $item) {
    // Accediendo a los valores dentro de cada elemento del array
    $tipoBillete = $item['tipo_billete'];
    $denominacion = $item['denominacion'];
    $billete = $item['billete'];

    $billete->estado = "vendido";
    $billete->usuario_id = $usuario->id;
    $billete->direccion_usuario = $denominacion['direccion'];
    $billete->dni_usuario = $denominacion['dni'];
    $billete->nombre_usuario = $denominacion['nombre'];
    $billete->fecha_compra = Carbon::now();
    $billete->save();
  
}

    



    // Devolver una respuesta con el contenido y el usuario
    return response()->json([
        'usuario' => $usuario,
        'billetes' => $billetes,
        'tickets' => $tickets,
        'vuelo' => $vuelo,
        'billetesAcomprar'=> $billetesAcomprar
    ], 200);
}
public function obtenerVuelos()
{
    $usuario = auth()->user();

    $tickets = Ticket::where('usuario_id', $usuario->id)->get();

    $vueloIds = $tickets->pluck('vuelo_id')->unique();

    $vuelos = Vuelo::whereIn('id', $vueloIds)->get();

    $datosVuelos = [];

    foreach ($vuelos as $vuelo) {
        $aeropuertoSalida = Airport::find($vuelo->origen_aeropuesto);
        $aeropuertoDestino = Airport::find($vuelo->destino_aeropuerto);

        $datosVuelos[] = [
            'vuelo' => $vuelo,
            'aeropuerto_salida' => $aeropuertoSalida,
            'aeropuerto_destino' => $aeropuertoDestino,
            'tickets' => $tickets->where('vuelo_id', $vuelo->id)->values(),
        ];
    }

    return response()->json([
        'vuelos' => $datosVuelos
    ], 200);
}


}
