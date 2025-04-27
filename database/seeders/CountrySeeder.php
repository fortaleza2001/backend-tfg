<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{


public function run(): void
{
    $countries = [
        ['name' => 'España', 'code' => 'ES'],
        ['name' => 'Francia', 'code' => 'FR'],
        ['name' => 'Alemania', 'code' => 'DE'],
        ['name' => 'Italia', 'code' => 'IT'],
        ['name' => 'Estados Unidos', 'code' => 'US'],
        ['name' => 'Reino Unido', 'code' => 'GB'],
        ['name' => 'Canadá', 'code' => 'CA'],
        ['name' => 'México', 'code' => 'MX'],
        ['name' => 'Brasil', 'code' => 'BR'],
        ['name' => 'Argentina', 'code' => 'AR'],
    ];

    foreach ($countries as $country) {
        Country::create($country); // Usando create en lugar de insert
    }
}

}
