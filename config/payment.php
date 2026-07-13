<?php

return [
    'notification_email' => env('PAYMENT_NOTIFICATION_EMAIL', 'jem.production26@gmail.com'),
    'bank' => [
        'name' => env('PAYMENT_BANK_NAME', 'BRI'),
        'account_name' => env('PAYMENT_ACCOUNT_NAME', 'Eric Fausta'),
        'account_number' => env('PAYMENT_ACCOUNT_NUMBER', '005101254771505'),
    ],
    'qris_payload_file' => env('PAYMENT_QRIS_PAYLOAD_FILE', 'pay/qris.txt'),
];
