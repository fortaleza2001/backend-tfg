<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AerolineasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('aerolineas')->insert([
            [
                'nombre' => 'Avianca',
                'direccion' => 'Avenida Calle 26 # 59-15, Bogotá, Colombia',
                'pais' => 'Colombia',
                'telefono' => '+57 1 401 3434',
                'email' => 'contacto@avianca.com',
                'usuario_administrador' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'American Airlines',
                'direccion' => 'P.O. Box 619616, MD 5675, DFW Airport, TX, USA',
                'pais' => 'Estados Unidos',
                'telefono' => '+1 800-433-7300',
                'email' => 'info@aa.com',
                'usuario_administrador' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Lufthansa',
                'direccion' => 'Venloer Str. 151-153, 50672 Köln, Alemania',
                'pais' => 'Alemania',
                'telefono' => '+49 69 86 799 799',
                'email' => 'service@lufthansa.com',
                'usuario_administrador' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
