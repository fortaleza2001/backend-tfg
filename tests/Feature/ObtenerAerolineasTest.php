<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Aerolinea;

class ObtenerAerolineasTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_obtener_todas_las_aerolineas()
    {
  

      
        // Realizar una petición GET a la ruta 'aerolineas'
        $response = $this->get('/aerolineas');

        // Asegurarse de que la respuesta sea exitosa (200 OK)
        $response->assertStatus(200);

        $response->assertJson([
            'mensaje' => 'Consulta exitosa',
        ]);

        // Verificar que el contenido es un array y tiene 3 o más elementos
        $response->assertJsonStructure([
            'contenido' => [],
        ]);

        $contenido = $response->json('contenido');
        $this->assertGreaterThanOrEqual(3, count($contenido), 'La respuesta debe tener al menos 3 aerolíneas.');
    }
    
}
