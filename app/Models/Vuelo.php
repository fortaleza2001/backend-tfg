<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vuelo extends Model
{
    use HasFactory;

    protected $table = 'vuelos';

    protected $fillable = [
        'aerolinea_id',
        'codigo_vuelo',
        'origen',
        'destino',
        'hora_salida',
        'hora_llegada',
        'capacidad',
        'asientos_disponibles',
        'estado',
    ];

    // Relación con Aerolínea
    public function aerolinea()
    {
        return $this->belongsTo(Aerolinea::class);
    }

    // Accesor para obtener el estado del vuelo con formato amigable
    public function getEstadoLabelAttribute()
    {
        return match ($this->estado) {
            'programado' => 'Programado',
            'en vuelo' => 'En Vuelo',
            'aterrizado' => 'Aterrizado',
            'cancelado' => 'Cancelado',
        };
    }
}
