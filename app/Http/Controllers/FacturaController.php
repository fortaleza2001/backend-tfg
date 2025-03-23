<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class FacturaController extends Controller
{
    public function download($id)
    {
        // Buscar la factura en la base de datos usando el ID
        $factura = DB::table('facturas')->where('id', $id)->first();

        // Verifica si la factura existe y si tiene un archivo PDF
        if ($factura && $factura->archivo_pdf) {
            // Decodificar el archivo PDF desde la base de datos
            $pdfData = $factura->archivo_pdf;

            return response()->stream(function () use ($pdfData) {
                echo $pdfData; // Aquí puedes enviar el contenido PDF en chunks
            }, 200, [
                'Content-Type' => 'application/pdf',  // El tipo de contenido del archivo
                'Content-Disposition' => 'attachment; filename="factura_' . $id . '.pdf"',  // Nombre del archivo
                'X-Message' => 'Factura descargada correctamente' // Mensaje en los encabezados
            ]);
        }

        // Si no se encuentra el archivo, devolver un error 404
        return response()->json(['message' => 'Factura no encontrada'], 404);
    }
}
