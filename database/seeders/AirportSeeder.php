<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('seeders/airports.csv'), 'r');
        $csv->setHeaderOffset(0);

       /**foreach ($csv->getRecords() as $record) {
            DB::table('airports')->insert([
                'ident' => $record['ident'],
                'type' => $record['type'],
                'name' => $record['name'],
                'latitude_deg' => $record['latitude_deg'] !== '' ? $record['latitude_deg'] : null,
                'longitude_deg' => $record['longitude_deg'] !== '' ? $record['longitude_deg'] : null,
                'elevation_ft' => $record['elevation_ft'] !== '' ? $record['elevation_ft'] : null,
                'iso_country' => $record['iso_country'],
                'municipality' => $record['municipality'],
                'iata_code' => $record['iata_code'],
                'icao_code' => $record['icao_code'],
                'gps_code' => $record['gps_code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
        }
        */
                
     
    }
}
