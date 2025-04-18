<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class datos_creador_aerolinea extends Model
{use HasFactory;
    protected $table = 'datos_creador_aerolinea'; // porque el nombre no es el plural convencional

    protected $fillable = [
        'nombreCreador',
        'dniCreador',
        'direccionCreador',
        'numeroTelefono',
        'fechaNacimientoCreador',
    ];

    public function aerolineas()
{
    return $this->hasMany(Aerolinea::class, 'id_datos_creador');
}

}
