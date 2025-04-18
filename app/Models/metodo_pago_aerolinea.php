<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class metodo_pago_aerolinea extends Model
{
    protected $table = 'datos_pagos';

    protected $fillable = [
        'nombre_titular',
        'numero_tarjeta',
        'fecha_caducidad',
        'cvv',
    ];
}
