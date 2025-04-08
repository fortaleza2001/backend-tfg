<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class loginUsuarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_usuario_puede_loguearse_y_obtener_un_token_jwt()
    {
        // Crear un usuario de prueba
        $user = User::factory()->create([
            'email' => 'juan@example.com',
            'password' => 'password123'
        ]);

        // Simular login
        $response = $this->postJson('/login', [
            'email' => 'juan@example.com',
            'password' => 'password123',
        ]);

        // Verificamos que se obtuvo el token JWT correctamente
        $response->assertStatus(200);
       
        $response->assertCookie('auth_token');

    }
}
