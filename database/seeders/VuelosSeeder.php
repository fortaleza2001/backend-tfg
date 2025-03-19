<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class VuelosSeeder extends Seeder
{
    public function run()
    {
        DB::table('vuelos')->insert([
            [
                'aerolinea_id' => 1,
                'codigo_vuelo' => 'AV123',
                'origen' => 'Bogotá',
                'destino' => 'Miami',
                'hora_salida' => '2025-03-20 08:00:00',
                'hora_llegada' => '2025-03-20 12:00:00',
                'capacidad' => 150,
                'asientos_disponibles' => 100,
                'estado' => 'programado',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'aerolinea_id' => 2,
                'codigo_vuelo' => 'AA456',
                'origen' => 'Nueva York',
                'destino' => 'Londres',
                'hora_salida' => '2025-03-21 15:30:00',
                'hora_llegada' => '2025-03-21 23:45:00',
                'capacidad' => 200,
                'asientos_disponibles' => 150,
                'estado' => 'en vuelo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'aerolinea_id' => 3,
                'codigo_vuelo' => 'LH789',
                'origen' => 'Berlín',
                'destino' => 'Tokio',
                'hora_salida' => '2025-03-22 22:00:00',
                'hora_llegada' => '2025-03-23 14:00:00',
                'capacidad' => 250,
                'asientos_disponibles' => 180,
                'estado' => 'programado',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
