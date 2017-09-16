<?php

return [
    'wechat' => [
        'schema' => 'https',
        'ssl' => true,
        'host' => 'api.weixin.qq.com',
        'port' => 80,
        'connect_timeout_ms' => 1000,
        'read_timeout_ms' => 2000,
        'retry' => 1,
        'is_sign' => false,
        'app_id' => 'wx5763b38a2613f9fb',
        'app_secret' => 'f9128ba451c51ce44fdd3bddf2fa45e7',
        'result' => [
            'error_code_field' => 'errcode',
            'error_code_success' => '',
            'error_data_field' => '',
        ]
    ],
    'client' => [
        'cy' => [
            'app_id' => 'snve1zlao934hhh323',
            'app_secret' => 'fiejfAJG%139*&sdfjNMJQA',
        ]
    ],
    'file' => [
        'path' => '/data/game/file/',
        'patch_name' => 'MyAssets.upk',
    ]
];
