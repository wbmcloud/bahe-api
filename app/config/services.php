<?php

return [
    'wechat' => [
        'schema' => 'https',
        'host' => 'api.weixin.qq.com',
        'port' => 80,
        'connect_time_out' => 1000,
        'read_time_out' => 2000,
        'retry' => 1,
        'is_sign' => false,
        'app_id' => 'wx5763b38a2613f9fb',
        'app_secret' => 'f9128ba451c51ce44fdd3bddf2fa45e7',
        'return' => [
            'code' => '',
            'message' => '',
        ]
    ]
];
