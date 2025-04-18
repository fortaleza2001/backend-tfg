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
        Schema::create('aerolineas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('pais');
            $table->string('telefono')->nullable(); 
            $table->string('email')->nullable();
            $table->string('codigo_AITA')->unique();
            $table->boolean('confirmado')->default(false);
            $table->string('token_confirmado')->nullable();
            
            $table->bigInteger("usuario_administrador");
            $table->string("logoAerolinea")->nullable();
            $table->bigInteger("id_datos_creador")->nullable();
            $table->bigInteger("id_datos_pago")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aerolineas');
    }
};
