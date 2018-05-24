<?php
/**
 * Copyright © 链家网（北京）科技有限公司
 * File: CommonConst.php
 * User: wangbaoming@lianjia.com
 * Date: 2018/3/11
 * Desc:
 */

namespace App\Common\Constant;

class CommonConst
{
    const BIZ_TYPE_CHAOYANG = 'chaoyang';
    const BIZ_TYPE_YINGKOU = 'yingkou';

    const DOWNLOAD_LOCAL_SERVER_DOMAIN = 'http://api.8here.cn';

    public static $local_download_app = [
        'snve1zlao934hhh323' => self::BIZ_TYPE_CHAOYANG
    ];

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;
}