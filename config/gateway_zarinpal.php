<?php

return [

    /**
     *  driver class namespace.
     */
    'driver' => Omalizadeh\MultiPayment\Drivers\Zarinpal\Zarinpal::class,

    /**
     *  gateway configurations.
     */
    'main' => [
        'authorization_token' => '', // optional, used only to refund payments (can be created from zarinpal panel)
        'merchant_id' => '8a57f48d-f8f8-4038-97b1-30972295563c',
        'callback_url' => 'https://wishestree.ir',
        'mode' => 'normal', // supported values: sandbox, normal, zaringate
        'description' => 'payment using zarinpal',
    ],
    'local' => [
        'authorization_token' => '', // optional, used only to refund payments (can be created from zarinpal panel)
        'merchant_id' => '8a57f48d-234a-4038-97b1-30974535563c',
        'callback_url' => 'http://localhost:5173',
        'mode' => 'sandbox', // supported values: sandbox, normal, zaringate
        'description' => 'payment using zarinpal',
    ],
];
