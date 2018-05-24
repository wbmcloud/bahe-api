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
    const IF_GAME_DOWNLOAD = '/game/download';
    const IF_GAME_HOTUPDATE = '/game/hotupdate';
    const IF_GAME_BINDAGENT = '/game/bindagent';

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
        ],
        self::IF_GAME_DOWNLOAD => [
            'ver' => 'required|integer',
        ],
        self::IF_GAME_DOWNLOAD => [
            'ver' => 'nullable|integer',
        ],
        self::IF_GAME_BINDAGENT => [
            'player_id' => 'required|numeric',
            'agent_id' => 'required|numeric',
            'type' => 'required|numeric',
        ],
    ];
}