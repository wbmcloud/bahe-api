<?php
/**
 * Copyright © 链家网（北京）科技有限公司
 * File: CommonConst.php
 * User: wangbaoming@lianjia.com
 * Date: 2018/3/11
 * Desc:
 */

namespace App\Common\Constant;

class GameConst
{
    const GAME_TYPE_MJ = 1;
    const GAME_TYPE_DDZ = 2;
    const GAME_TYPE_ZT = 3;

    const CLIENT_TYPE_ANDROID = 1;
    const CLIENT_TYPE_IOS = 2;
    const CLIENT_TYPE_WIN = 3;

    public static $game_type_map = [
        self::GAME_TYPE_DDZ,
    ];

    public static $client_type_text_map = [
        self::CLIENT_TYPE_ANDROID => 'android',
        self::CLIENT_TYPE_IOS => 'ios',
        self::CLIENT_TYPE_WIN => 'win'
    ];
}