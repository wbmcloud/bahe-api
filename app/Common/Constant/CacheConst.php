<?php

namespace App\Common\Constant;

class CacheConst
{
    const SERVER_DOWN_MAINTAIN = 'SERVER_DOWN_MAINTAIN';

    const JWT_NONCE = 'JWT_NONCE';

    const HOT_UPDATE_FILE_ISSUES = 'HOT_UPDATE_FILE_ISSUES';

    const HOT_UPDATE_ISSUE_VERSION = 'HOT_UPDATE_ISSUE_VERSION';

    public static $cache_alive = [
        self::JWT_NONCE => 60,
    ];
}