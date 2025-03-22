<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AerolineasSeeder;
use Database\Seeders\VuelosSeeder;

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
            'aerolinea_id'=>1
        ]);
        $this->call(AerolineasSeeder::class);
        $this->call(VuelosSeeder::class);

    }
}
