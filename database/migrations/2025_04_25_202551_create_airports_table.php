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
    if (!Schema::hasTable('airports')) {
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->nullable();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->decimal('latitude_deg', 10, 6)->nullable();
            $table->decimal('longitude_deg', 10, 6)->nullable();
            $table->integer('elevation_ft')->nullable();
            $table->string('iso_country', 2)->nullable();
            $table->string('municipality')->nullable();
            $table->string('iata_code', 10)->nullable();
            $table->string('icao_code', 10)->nullable();
            $table->string('gps_code', 10)->nullable();
            $table->timestamps();
        });
    }
}


    /**
     * Reverse the migrations.
     */
    
};
