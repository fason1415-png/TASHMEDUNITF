<?php

return [
    // ShifoReyting Bot - so'rovnoma uchun
    'bot_token' => env('TELEGRAM_BOT_TOKEN', '8545449882:AAH1vp-CTMIee_twN3h0G51_QBsmJZGU4Yo'),
    'bot_username' => env('TELEGRAM_BOT_USERNAME', 'ShifoReytingBot'),
    'webhook_url' => env('TELEGRAM_WEBHOOK_URL'),

    // Patronaj Bot - patronaj tizimi uchun
    'patronage_bot_token' => env('PATRONAGE_BOT_TOKEN', '8678531550:AAEK_WmDQhIQtRqafoHG1dPjHhAdLLYv418'),
    'patronage_bot_username' => env('PATRONAGE_BOT_USERNAME', 'Potranaj_bot'),
    'patronage_webhook_url' => env('PATRONAGE_WEBHOOK_URL'),
];
