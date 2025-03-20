<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


/**
 * @OA\Info(
 *     title="API de Aerolíneas (servidor Desarrollo)",
 *     version="0.0.1",
 *     description="Documentación de la API para la gestión de aerolíneas",
 *     @OA\Contact(
 *         name="Soporte API",
 *         email="juancarrasquer@gmail.com"
 *     )
 * )
 */

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
