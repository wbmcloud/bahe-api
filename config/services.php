<?php

return [
    'wechat' => [
        'schema' => 'https',
        'ssl' => true,
        'host' => 'api.weixin.qq.com',
        'port' => 80,
        'connect_timeout_ms' => 1000,
        'read_timeout_ms' => 2000,
        'retry' => 3,
        'is_sign' => false,
        'result' => [
            'error_code_field' => 'errcode',
            'error_code_success' => '',
            'error_data_field' => '',
        ]
    ],
    'client' => [
        'snve1zlao934hhh323' => [
            'app_name' => 'chaoyang',
            'app_id' => 'snve1zlao934hhh323',
            'app_secret' => 'fiejfAJG%139*&sdfjNMJQA',
            'wx' => [
                'app_id' => 'wx5763b38a2613f9fb',
                'app_secret' => 'f9128ba451c51ce44fdd3bddf2fa45e7',
            ],
            'city_id' => 2340,
            'download' => [
                'path'      => '/data/game/file/',
                'file_name' => 'MyAssets_issue%s.upk',
            ]
        ],
        'we9rajfksnnp123dfs' => [
            'app_name' => 'yingkou',
            'app_id' => 'we9rajfksnnp123dfs',
            'app_secret' => 'jlajdsfk@*()*&AHFGNKEAL',
            'wx' => [
                'app_id' => 'wx5fae537bf4e03e20',
                'app_secret' => 'cd69ad3b6df6ad5362887d7cdf8708c1',
            ],
            'city_id' => 2280,
        ],
        'ew123e9r1alli123fs' => [
            'app_name' => 'doudizhu',
            'app_id' => 'ew123e9r1alli123fs',
            'app_secret' => 'ELkfjIfk@*()*&AHFGBpqiL',
            'wx' => [
                'app_id' => 'wxca31a5bc68984f8d',
                'app_secret' => 'badbaf9b1c078fe8c944b1009fb582e9',
            ],
        ],
        'faiejfjkn14jsk945k' => [
            'app_name' => 'president',
            'app_id' => 'faiejfjkn14jsk945k',
            'app_secret' => 'JF)KLJIEWNXPPjkj8wjj1lju',
            'download' => [
                'path'      => '/data/game/file/',
                'file_name' => '%s/MyAssets_issue%s.upk',
            ],
            'is_dis_platform' => true,
        ]
    ]
];
