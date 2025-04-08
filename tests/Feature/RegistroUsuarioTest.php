<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegistroUsuarioTest extends TestCase
{
   

    /** @test */
    public function un_usuario_puede_registrarse_correctamente()
    {
        $datos = [
            'name' => 'Juan Carrasquer',
            'email' => 'juan@example.com',
            'password' => 'password123',
            
        ];

        $response = $this->post('/register', $datos);

        $response->assertStatus(201); 
        $this->assertDatabaseHas('users', [
            'email' => 'juan@example.com',
        ]);

       
    }
}
