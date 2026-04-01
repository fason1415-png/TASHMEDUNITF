<?php

return [
    'qr_public_base_url' => env('QR_PUBLIC_BASE_URL', env('APP_URL')),

    'supported_locales' => [
        'uz_latn' => "O'zbekcha",
        'uz_cyrl' => 'Uzbekcha (Cyrl)',
        'ru' => 'Russkiy',
        'en' => 'English',
    ],

    'channel_values' => ['qr', 'shortlink', 'kiosk', 'telegram', 'sms'],

    'default_scoring_weights' => [
        'service_quality' => 0.25,
        'communication' => 0.20,
        'waiting_experience' => 0.15,
        'explanation_quality' => 0.20,
        'sentiment' => 0.15,
        'resolution_quality' => 0.05,
    ],

    'minimum_public_samples' => 10,

    'plans' => [
        'start' => [
            'max_doctors' => 50,
            'max_branches' => 2,
            'max_monthly_feedback' => 5000,
        ],
        'standard' => [
            'max_doctors' => 200,
            'max_branches' => 10,
            'max_monthly_feedback' => 50000,
        ],
        'enterprise' => [
            'max_doctors' => null,
            'max_branches' => null,
            'max_monthly_feedback' => null,
        ],
    ],

    'roles' => [
        'super_admin',
        'clinic_admin',
        'branch_manager',
        'doctor',
        'analyst',
        'support_moderator',
    ],
];
