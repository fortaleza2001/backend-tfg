<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = [
        'ident',
        'type',
        'name',
        'latitude_deg',
        'longitude_deg',
        'elevation_ft',
        'iso_country',
        'municipality',
        'iata_code',
        'icao_code',
        'gps_code',
    ];
}
