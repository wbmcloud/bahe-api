<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/18
 * Time: 上午8:53
 */

namespace App\Common\Check;

class ParamsRules
{

    const IF_WECHAT_USREINFO   = '/wechat/userinfo';

    /**
     * @var array
     * 参数校验规则
     */
    public static $rules = [
        self::IF_WECHAT_USREINFO              => [
            'code'        => 'required|string',
        ],
    ];
}