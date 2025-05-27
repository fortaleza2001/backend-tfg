<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vuelo;
use Carbon\Carbon;

class ActualizarEstadoVuelos extends Command
{
    protected $signature = 'vuelos:actualizar-estado';
    protected $description = 'Actualiza el estado de los vuelos según la fecha y hora actuales 2';

    public function handle()
{
    $ahora = Carbon::now('Europe/Madrid');


    // Cambiar estado de "programado" a "en vuelo"
    $this->line("Iniciando actualización de vuelos...");

    $vuelosProgramados = Vuelo::where('estado', 'programado')->get();

    $this->line("Vuelos programados encontrados: " . $vuelosProgramados->count());

    Vuelo::where('estado', 'programado')
        ->where('fecha_salida', '<=', $ahora)
        ->update(['estado' => 'en vuelo']);

    $this->info("Vuelos actualizados a 'en vuelo'.");

    // Cambiar estado de "en vuelo" a "aterrizado" si ya pasó la llegada
    $vuelosEnVuelo = Vuelo::where('estado', 'en vuelo')->get();

    $this->line("Vuelos en vuelo encontrados: " . $vuelosEnVuelo->count());

    Vuelo::where('estado', 'en vuelo')
        ->where('fecha_llegada', '<=', $ahora)
        ->update(['estado' => 'aterrizado']);

    $this->line("Vuelos actualizados a 'aterrizado'.");

    $this->line('Estados de vuelos actualizados correctamente.');
}

}
