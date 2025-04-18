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
        Schema::create('datos_pagos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_titular'); // Nombre completo en la tarjeta
            $table->string('numero_tarjeta'); // Número de tarjeta (⚠️ no recomendado almacenar completo)
            $table->string('fecha_caducidad'); // Ej: 12/27
            $table->string('cvv'); // Código de seguridad (⚠️ no recomendado almacenar)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_pagos');
    }
};
