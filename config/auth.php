<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'usuarios'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'usuarios', // 👈 ESTE NOMBRE TIENE QUE EXISTIR ABAJO
        ],
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'usuarios',
        ],
    ],

    'providers' => [
        'usuarios' => [ // 👈 CAMBIADO de 'clientes' a 'usuarios'
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class, // 👈 Este es tu modelo correcto
        ],
    ],

    'passwords' => [
        'usuarios' => [
            'provider' => 'usuarios',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
