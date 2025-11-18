<?php

return [

    'defaults' => [
        'guard' => 'web', // default guard là web (dùng cho user)
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'staff' => [
            'driver' => 'session',
            'provider' => 'staffs',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'staffs' => [
            'driver' => 'eloquent',
            'model' => App\Models\Staff::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
