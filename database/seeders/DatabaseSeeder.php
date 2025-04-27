<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AerolineasSeeder;
use Database\Seeders\VuelosSeeder;
use Database\Seeders\FacturaSeeder;
use Database\Seeders\AirportSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password'=>'1234',
            
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'juancarrasquer@gmail.com',
            'password'=>'725728848004380084580',
           
        ]);

        $this->call(AerolineasSeeder::class);
        $this->call(VuelosSeeder::class);
        $this->call(FacturaSeeder::class);
        //$this->call(AirportSeeder::class);

    }
}
