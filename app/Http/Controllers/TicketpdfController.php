<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Vuelo;
use App\Models\Airport;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class TicketpdfController extends Controller
{
    

    public function generarTicket($id)
    {
         $usuario = auth()->user();
        
        $ticket = Ticket::find($id);
        $vuelo_entidad = Vuelo::find($ticket->vuelo_id);
        $aeropuertoSalida = Airport::find($vuelo_entidad->origen_aeropuesto);
        $aeropuertoDestino = Airport::find($vuelo_entidad->destino_aeropuerto);
        // Datos del ticket
        $vuelo = $aeropuertoSalida->name . " -> " . $aeropuertoDestino->name;
        $fecha_salida = Carbon::parse($vuelo_entidad->fecha_salida)->format('d-m-Y');
        $desde = $aeropuertoSalida->name . " (" . $aeropuertoSalida->iso_country . ')';
        $destino = $aeropuertoDestino->name . " (" . $aeropuertoDestino->iso_country . ')';
        $comprador = $ticket->nombre_usuario;
        $email = $usuario->email;
        $precio =  $ticket->precio . '€ ';
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate('string'));

    
        // Crear el PDF con los datos del ticket
        $pdf = Pdf::loadView('ticket', [
            'vuelo' => $vuelo,
            'fecha_salida' => $fecha_salida,
            'desde' => $desde,
            'destino' => $destino,
            'comprador' => $comprador,
            'email' => $email,
            'precio' => $precio,
            'qrCode'=> $qrcode
        ]);

        // Retornar el PDF para descargarlo
        return $pdf->download('ticket-vuelo.pdf');
    }


    public function generarFactura($id)
    {
         $usuario = auth()->user();
        $vuelo_entidad = Vuelo::find($id);
        $aeropuertoSalida = Airport::find($vuelo_entidad->origen_aeropuesto);
        $aeropuertoDestino = Airport::find($vuelo_entidad->destino_aeropuerto);
        $billetes = Ticket::where("vuelo_id",$vuelo_entidad->id)->where("usuario_id",$usuario->id)->get();

        // Datos simulados para la factura
        $fecha = Carbon::parse($billetes[0]->fecha_compra)->format('d/m/Y');

        $numero_factura = $vuelo_entidad->codigo_vuelo .  $usuario->id;
        $email_cliente = $usuario->email;
        $nombre_vuelo= $aeropuertoSalida->name . " -> " . $aeropuertoDestino->name;;

        $tipos = $billetes->pluck('tipo')->unique();



        // Productos de la factura
        $productos = [
        ];

        foreach ($tipos as $tipo) 
        {
            $x=0;
            $total =0;
            $precio_unidad =0;
            $descripcion = "billete " . $tipo;

            foreach ($billetes as $billete) {
                if($billete->tipo == $tipo)
                {
                    $x++;
                    $total = $total + $billete->precio;
                    $precio_unidad = $billete->precio;

                }
            }
                $productos[] = [
            'descripcion' => $descripcion,
            'cantidad' => $x,
            'precio_unitario' => $precio_unidad,
            'total' => $total
            ];
                
        
        }

     


        // Calcular el total
        $total = array_sum(array_column($productos, 'total'));

        // Cargar la vista Blade para la factura
        $pdf = Pdf::loadView('factura2', [
            'fecha' => $fecha,
            'numero_factura' => $numero_factura,
            'email_cliente' => $email_cliente,
            'productos' => $productos,
            'total' => $total,
            'nombre_vuelo'=> $nombre_vuelo
        ]);

        // Retornar el PDF para su descarga
        return $pdf->download('factura_' . $numero_factura . '.pdf');
    }
}
