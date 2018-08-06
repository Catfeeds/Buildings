<?php

return [
    'scopes' => [
        'place-orders' => 'Place orders',
        'check-status' => 'Check order status',
        '*' => 'all scopes',
    ],

    'default' => [
        'client_id' => env('CLIENT_ID'),
        'client_secret' => env('CLIENT_SECRET'),
        'scope' => '*',
    ],

    // 访问令牌有效期（天）
    'tokensExpireIn' => 15,

    // 刷新后的访问令牌有效期（天）
    'refreshTokensExpireIn' => 30,
];