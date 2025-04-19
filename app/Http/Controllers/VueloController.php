<?php

namespace App\Http\Controllers;

use App\Models\Vuelo;
use App\Models\Aerolinea;
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
    $vuelo_creado->origen_aeropuesto = $request->vuelo['aeropuesto_origen'];
    $vuelo_creado->destino_aeropuerto = $request->vuelo['aeropuesto_destino'];
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
        // Buscar vuelos por el ID de la aerolínea
        $vuelos = Vuelo::where('aerolinea_id', $id)->get();
    
        // Verificar si no se encontraron vuelos
        if ($vuelos->isEmpty()) {
            return response()->json([
                'mensaje' => 'No se encontraron vuelos para esta aerolínea',
                'contenido' => []
            ], 404); // Código de respuesta 404 para no encontrado
        }
    
        // Si hay vuelos, devolverlos
        return response()->json([
            'mensaje' => 'Consulta exitosa',
            'contenido' => $vuelos
        ], 200); // Código de respuesta 200 para éxito
    }
    
}
