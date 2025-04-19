<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vuelos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aerolinea_id'); // Relación con aerolíneas
            $table->string('codigo_vuelo')->unique(); // Código de vuelo único (ej: AV123)
            $table->string('origen_pais'); // Ciudad o aeropuerto de origen
            $table->string('destino_pais'); // Ciudad o aeropuerto de destino
            $table->string('origen_aeropuesto'); // Ciudad o aeropuerto de origen
            $table->string('destino_aeropuerto'); // Ciudad o aeropuerto de destino
            $table->string('origen_terminal'); // Ciudad o aeropuerto de origen
            $table->string('destino_terminal'); // Ciudad o aeropuerto de destino
            $table->dateTime('fecha_salida'); // Hora de salida del vuelo
            $table->dateTime('fecha_llegada'); // Hora estimada de llegada
            $table->integer('capacidad_pasajeros'); // Capacidad total de pasajeros
            $table->integer('capacidad_tickets'); // Capacidad total de pasajeros
            $table->enum('estado', ['programado', 'en vuelo', 'aterrizado', 'cancelado'])->default('programado'); // Estado del vuelo
            $table->timestamps();
        
            
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vuelos');
    }
};
