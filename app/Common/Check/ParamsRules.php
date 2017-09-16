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
    const IF_AUTH_JWT = '/auth/jwt';

    /**
     * @var array
     * 参数校验规则
     */
    public static $rules = [
        self::IF_WECHAT_USREINFO              => [
            'code'        => 'string|nullable',
            'open_id'     => 'string|nullable',
        ],
        self::IF_AUTH_JWT => [
            'app_id' => 'required|string',
            'time' => 'required|string',
            'nonce' => 'required|string|size:7',
            'sign' => 'required|string',
        ]
    ];
}