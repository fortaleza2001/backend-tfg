<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;
use App\Models\Vuelo;
use App\Models\Aerolinea;
use App\Models\Airport;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
class VueloController extends Controller
{
    public function generarCodigoVuelo($id)
    {
        $aerolinea = Aerolinea::find($id)->first();

        $aerolineaNombre=$aerolinea->nombre;
        // Obtener las primeras 2 letras del nombre de la aerolínea (en mayúsculas)
        $primerasLetras = strtoupper(substr($aerolineaNombre, 0, 2));
    
        // Generar los 5 caracteres aleatorios (letras y números)
        $caracteresAleatorios = Str::random(5);
    
        // Formar el código final
        $codigoVuelo = $primerasLetras . $caracteresAleatorios;
    
        // Comprobar que el código no exista ya en la base de datos
        while (Vuelo::where('codigo_vuelo', $codigoVuelo)->exists()) {
            // Si existe, generar otro código
            $caracteresAleatorios = Str::random(5);
            $codigoVuelo = $primerasLetras . $caracteresAleatorios;
        }
    
        return $codigoVuelo;
    }
    public function obtenerVuelo($id)
    {
        $vuelo = Vuelo::find($id);

        if($vuelo==null ||$vuelo->estado!="programado" )
        {
            return response("Vuelo no valido para visualizar en compra",402);
        }

        $aeropuertoSalida = Airport::find($vuelo->origen_aeropuesto);
        $aeropuertoDestino = Airport::find($vuelo->destino_aeropuerto);

        $tickets = Ticket::where('vuelo_id', $vuelo->id)
        ->select('tipo', 'precio', 'estado',"vuelo_id")
        ->get();
    
        $tiposDisponibles = $tickets
    ->where('estado', 'disponible')
    ->groupBy('tipo')
    ->map(function (Collection $items, $tipo) {
        return [
            'tipo' => $tipo,
            'precio' => $items->first()->precio,
            'disponibles' => $items->count(),
            'id_vuelo' => $items->first()->vuelo_id, 
        ];
    })
    ->values(); // Convierte a array plano si lo necesitas

        $coincidencias_tipo_billete = $tiposDisponibles;
    
        return response()->json([
            'mensaje' => 'Vuelos encontrados',
            'vuelo' => $vuelo,
            'ida' => $aeropuertoSalida,
            'llegada' => $aeropuertoDestino,
            'billetes' => $coincidencias_tipo_billete
        ], 200);


    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vuelo.paisOrigen' => 'required|string|max:255',
            'vuelo.aeropuesto_origen' => 'required|string|max:255',
            'vuelo.terminal_origen' => 'required|string|max:255',
            'vuelo.pais_destino' => 'required|string|max:255',
            'vuelo.aeropuesto_destino' => 'required|string|max:255',
            'vuelo.terminal_destino' => 'required|string|max:255',
            'vuelo.fecha_salida' => 'required|date',
            'vuelo.fecha_llegada' => 'required|date',
            'vuelo.numero_tickets' => 'required|integer',
            'vuelo.numero_pasajeros' => 'required|integer',
            'id_aerolinea'=> 'required|integer',
            'tickets.*.tipo' => 'required|string|max:255',
            'tickets.*.precio' => 'required|numeric',
            'tickets.*.cantidadDisponible' => 'required|integer|min:1',
            'tickets.*.equipajeIncluidoKg' => 'required|integer|min:0',
            'tickets.*.reembolsable' => 'required|boolean',


        ]);
       
        
         // Convertir la fecha a formato adecuado para MySQL
    $fechaSalida = Carbon::parse($request->vuelo['fecha_salida'])->format('Y-m-d H:i:s');
    $fechaLlegada = Carbon::parse($request->vuelo['fecha_llegada'])->format('Y-m-d H:i:s');
 

        // Crear el vuelo
        $codigo_vuelo = $this->generarCodigoVuelo($request->id_aerolinea);

        // Crear un nuevo objeto de Vuelo
        $vuelo_creado = new Vuelo();
        $vuelo_creado->aerolinea_id = $request->id_aerolinea;
        $vuelo_creado->codigo_vuelo = $codigo_vuelo;
        
         // Acceder a los valores como arreglo (con corchetes)
    $vuelo_creado->origen_pais = $request->vuelo['paisOrigen'];
    $vuelo_creado->destino_pais = $request->vuelo['pais_destino'];
    $vuelo_creado->origen_aeropuesto = $request->vuelo['aeropuesto_origen_id'];
    $vuelo_creado->destino_aeropuerto = $request->vuelo['aeropuesto_final_id'];
    $vuelo_creado->origen_terminal = $request->vuelo['terminal_origen'];
    $vuelo_creado->destino_terminal = $request->vuelo['terminal_destino'];
    $vuelo_creado->fecha_salida = $fechaSalida;
    $vuelo_creado->fecha_llegada =  $fechaLlegada;
    $vuelo_creado->capacidad_pasajeros = $request->vuelo['numero_pasajeros'];
    $vuelo_creado->capacidad_tickets = $request->vuelo['numero_tickets'];
    
        // Guardar el vuelo en la base de datos
        $vuelo_creado->save();
        $n=1;
        for ($i = 0; $i < count($request->tickets); $i++) {
            $ticketData = $request->tickets[$i];
            for ($j = 0; $j < $ticketData['cantidadDisponible']; $j++) {
                
        
                $ticket = new Ticket();
                $ticket->vuelo_id = $vuelo_creado->id; // Asocia el ticket al vuelo
                $ticket->tipo = $ticketData['tipo'];
                $ticket->precio = $ticketData['precio'];
                $ticket->equipajeIncluidoKg = $ticketData['equipajeIncluidoKg'];
                $ticket->reembolsable = $ticketData['reembolsable'];
                $ticket->estado = 'disponible';
                $ticket->numero_ticket = $codigo_vuelo . "#" . ($n + 1);
                $ticket->save();
                $n++;
            }
           
        }
        
       
        return response()->json([
            'message' => 'Vuelo Creado correctamente y tickets creados asociados listos para ser vendidos',
            
        ],201);
    }

 public function obtenerVuelos($id)
{
    // Obtener los vuelos de la aerolínea
    $vuelos = Vuelo::where('aerolinea_id', $id)->get();

    // Verificar si no hay vuelos
    if ($vuelos->isEmpty()) {
        return response()->json([
            'mensaje' => 'No se encontraron vuelos para esta aerolínea',
            'contenido' => []
        ], 404);
    }

    // Recorrer vuelos y añadir información de aeropuertos
    $vuelosConAeropuertos = $vuelos->map(function ($vuelo) {
        $aeropuerto_origen = Airport::find($vuelo->origen_aeropuesto);
        $aeropuerto_destino = Airport::find($vuelo->destino_aeropuerto);
        return [
            'id' => $vuelo->id,
            'aerolinea_id'=>$vuelo->aerolinea_id,
            'codigo_vuelo'=>$vuelo->codigo_vuelo,
            'origen_pais'=>$vuelo->origen_pais,
            'destino_pais'=>$vuelo->destino_pais,
            'origen_terminal'=>$vuelo->origen_terminal,
            'destino_terminal'=>$vuelo->destino_terminal,
    
            'fecha_salida' => $vuelo->fecha_salida,
                
            'fecha_llegada' => $vuelo->fecha_llegada,
            'capacidad_tickets' => $vuelo->capacidad_tickets,
            'estado' => $vuelo->estado,
            'aeropuerto_origen' =>  $aeropuerto_origen, 
            'aeropuerto_destino' => $aeropuerto_destino,
        ];
    });
     
   

    return response()->json([
        'mensaje' => 'Consulta exitosa',
        'contenido' => $vuelosConAeropuertos
    ], 200);
}

    
     public function obtenerVueloReserva($id)
    {$usuario = auth()->user();

         $vuelo = Vuelo::find($id);

        $aeropuertoSalida = Airport::find($vuelo->origen_aeropuesto);
        $aeropuertoDestino = Airport::find($vuelo->destino_aeropuerto);

        $tickets = Ticket::where('vuelo_id', $vuelo->id)->where("usuario_id",$usuario->id)
        ->get();


        return response()->json([
            'mensaje' => 'Consulta exitosa',
            'reservas'=>$tickets,
            'salida' => $aeropuertoSalida,
            'vuelta' => $aeropuertoDestino,
        ], 200); // Código de respuesta 200 para éxito

    }


    public function buscarVuelos(Request $request)
    {
        // Obtener los datos enviados desde Angular
        $datos = $request->validate([
            'paisOrigen' => 'required|string',
            'origen' => 'required|string',
            'paisDestino' => 'required|string',
            'destino' => 'required|string',
            'fechaSalida' => 'required|date',
            'pasajeros' => 'required|integer|min:1',
        ]);
        
        $coincidencias = []; // Lista para guardar vuelos coincidentes
        $coincidencias_origen = []; // Lista para guardar vuelos coincidentes
        $coincidencias_destino = []; // Lista para guardar vuelos coincidentes
        $coincidencias_tipo_billete = []; // Lista para guardar vuelos coincidentes

// Obtener todos los vuelos con estado programado y fecha válida
$vuelos = Vuelo::whereDate('fecha_salida', date('Y-m-d', strtotime($datos['fechaSalida'])))
    ->where('capacidad_tickets', '>=', $datos['pasajeros'])
    ->where('estado', 'programado')
    ->get();

    
foreach ($vuelos as $vuelo) {
    $aeropuertoSalida = Airport::find($vuelo->origen_aeropuesto);
    $aeropuertoDestino = Airport::find($vuelo->destino_aeropuerto);
    if (!$aeropuertoSalida || !$aeropuertoDestino) {
        continue; // Saltar si no se encuentra alguno de los aeropuertos
    }

    // Comprobaciones de coincidencia
    $coincideOrigen = (
        str_contains(strtolower($aeropuertoSalida->nombre), strtolower($datos['origen'])) ||
        str_contains(strtolower($aeropuertoSalida->municipality), strtolower($datos['origen']))
    ) ? 1 : 0;
    
    $coincideDestino = (
        str_contains(strtolower($aeropuertoDestino->nombre), strtolower($datos['destino'])) ||
        str_contains(strtolower($aeropuertoDestino->municipality), strtolower($datos['destino']))
    ) ? 1 : 0;
    
    $coincidePaisOrigen = strtolower($vuelo->origen_pais) === strtolower($datos['paisOrigen']);
    $coincidePaisDestino = strtolower($vuelo->destino_pais) === strtolower($datos['paisDestino']);

    if ($coincideOrigen && $coincideDestino && $coincidePaisOrigen && $coincidePaisDestino) {
        $coincidencias[] = $vuelo;
        $coincidencias_origen[] = $aeropuertoSalida;
        $coincidencias_destino[] = $aeropuertoDestino;

        $tickets = Ticket::where('vuelo_id', $vuelo->id)
        ->select('tipo', 'precio', 'estado',"vuelo_id")
        ->get();

        $tiposDisponibles = $tickets
    ->where('estado', 'disponible')
    ->groupBy('tipo')
    ->map(function (Collection $items, $tipo) {
        return [
            'tipo' => $tipo,
            'precio' => $items->first()->precio,
            'disponibles' => $items->count(),
            'id_vuelo' => $items->first()->vuelo_id, 
        ];
    })
    ->values(); // Convierte a array plano si lo necesitas

        foreach ($tiposDisponibles as $tipo) {
    $coincidencias_tipo_billete[] = $tipo;
}

    }


}
    


                
        return response()->json([
            'mensaje' => 'Vuelos encontrados',
            'coincidencias' => $coincidencias,
            'salidas' => $coincidencias_origen,
            'llegadas' => $coincidencias_destino,
            'billetes' => $coincidencias_tipo_billete
        ], 200);
    }

  

}
