<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    use HasFactory;
    protected $fillable = [
        'tipo',
        'numero_ticket',
        'precio',
        'estado',
        'vuelo_id',
        'usuario_id',
        'reembolsable',
        'equipajeIncluidoKg',
    ];
  

    // Relación con el modelo Vuelo
    public function vuelo()
    {
        return $this->belongsTo(Vuelo::class);
    }
}
