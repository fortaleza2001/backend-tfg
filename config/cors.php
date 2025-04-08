<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Options
    |--------------------------------------------------------------------------
    |
    | Here you can specify all of the settings for CORS. You can enable CORS
    | for your whole application or just for specific routes. You can specify
    | which domains are allowed to access your application.
    |
    */

    'supports_credentials' => true, // Permitir el envío de credenciales como cookies

    'allowed_origins' => [
        'http://localhost:4200',  // URL de tu frontend (Angular, por ejemplo)
        // Otros dominios permitidos, si los hay.
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],  // Permitir todos los encabezados
    'allowed_methods' => ['*'],  // Permitir todos los métodos HTTP

    'exposed_headers' => ['*'],  // Exponer todos los encabezados (si es necesario)
    
    'max_age' => 0,

    'paths' => ['*', 'sanctum/csrf-cookie'],  // Asegúrate de incluir la ruta de Sanctum

];
