<?php

namespace App\Common\Constant;

class CacheConst
{
    const SERVER_DOWN_MAINTAIN = 'SERVER_DOWN_MAINTAIN';

    const JWT_NONCE = 'JWT_NONCE';

    public static $cache_alive = [
        self::JWT_NONCE => 300,
    ];
}