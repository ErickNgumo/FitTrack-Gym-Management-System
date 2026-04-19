<?php

return [

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        // Admin / Staff portal (existing)
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        // Member self-service portal
        'member' => [
            'driver'   => 'session',
            'provider' => 'member_credentials',
        ],

        // Trainer portal
        'trainer' => [
            'driver'   => 'session',
            'provider' => 'trainer_credentials',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        'member_credentials' => [
            'driver' => 'eloquent',
            'model'  => App\Models\MemberCredential::class,
        ],

        'trainer_credentials' => [
            'driver' => 'eloquent',
            'model'  => App\Models\TrainerCredential::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
