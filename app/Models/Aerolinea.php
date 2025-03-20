<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aerolinea extends Model
{
    use HasFactory;

    protected $table = 'aerolineas';

    protected $fillable = [
        'nombre',
        'direccion',
        'pais',
        'telefono',
        'email',
        'usuario_administrador',
    ];

    // Relación con Vuelos
    public function vuelos()
    {
        return $this->hasMany(Vuelo::class);
    }

    // Método para obtener el administrador (suponiendo que haya un modelo Usuario)
    public function administrador()
    {
        return $this->belongsTo(User::class, 'usuario_administrador');
    }
}
