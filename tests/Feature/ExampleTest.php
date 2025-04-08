<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
           // Ejecutar las migraciones y refrescar la base de datos
           $this->artisan('migrate:fresh');

           // Ejecutar los seeders para poblar la base de datos con datos de prueba
           $this->artisan('db:seed');

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
