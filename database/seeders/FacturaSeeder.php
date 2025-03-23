<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class FacturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Datos de la factura
        $factura = (object) [
            "id" => 123,
            "usuario" => "Juan Pérez",
            "numero" => "AB123",
            
            "codigo" => "TICKET12345",
            
            "fecha_emision" => "2025-03-23",
            "archivo_pdf" => null
        ];
        

        // Generar el PDF con los datos de la factura
        $pdf = PDF::loadView('factura', ['factura' => $factura]);

        // Convertir el PDF a formato binario
        $pdfBinary = $pdf->output();

        // Insertar la factura con el archivo PDF binario en la base de datos
        DB::table('facturas')->insert([
            'id' => $factura->id,  // Insertar el ID de la factura
            'archivo_pdf' => $pdfBinary,  // Guardar el archivo PDF en formato binario
        ]);
    }
}
