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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('numero_ticket');
            $table->decimal('precio', 8, 2); // hasta 999,999.99
            $table->enum('estado', ['disponible', 'vendido'])->default('disponible');
            $table->bigInteger('vuelo_id');
            $table->bigInteger('usuario_id')->nullable();
            $table->boolean("reembolsable")->nullable();
            $table->integer("equipajeIncluidoKg");
            $table->string('direccion_usuario')->nullable();
            $table->string('nombre_usuario')->nullable();
            $table->string('dni_usuario')->nullable();
            $table->binary("ticket_pdf")->nullable();
            $table->date("fecha_compra")->nullable();

            $table->timestamps();
        });
    }

   
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
