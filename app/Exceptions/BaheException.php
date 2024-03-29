<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/11
 * Time: 下午12:37
 */

namespace App\Exceptions;

class BaheException extends \Exception
{

    const API_CLIENT_MISS_CONFIG = 100100;
    const API_CLIENT_RETURN_NOT_JSON = 100101;
    const API_CLIENT_CALL_FAIL = 100102;
    const API_NOT_FOUND = 100103;
    const API_ARGS_NOT_VALID = 100104;
    const API_SIG_NOT_VALID = 100105;
    const API_REQUEST_NOT_VALID = 100106;
    const API_UNKNOWN_ERROR = 100107;

    const JWT_NOT_EXIST = 200100;
    const JWT_NOT_VALID = 200101;

    const WECHAT_CODE_NOT_VALID = 200200;
    const WECHAT_REFRESH_TOKEN_NOT_VALID = 200201;

    const SYS_CONFIG_NOT_FOUND = 200300;

    const APP_ID_NOT_VALID = 200400;
    const APP_TIME_NOT_VALID = 200401;
    const APP_NONCE_NOT_VALID = 200402;

    const AGENT_UK_NOT_VALID_CODE = 200403;
    const GAME_TYPE_NOT_VALID_CODE = 200404;

    public static $error_msg = [
        self::API_CLIENT_MISS_CONFIG => '配置文件不存在',
        self::API_CLIENT_RETURN_NOT_JSON => '返回的非json数据',
        self::API_CLIENT_CALL_FAIL => '接口调用失败',
        self::API_ARGS_NOT_VALID => '参数不合法',
        self::API_NOT_FOUND => 'API不存在',
        self::API_SIG_NOT_VALID => 'API签名不合法',
        self::JWT_NOT_EXIST => 'JWT不存在',
        self::JWT_NOT_VALID => 'JWT不合法',
        self::WECHAT_CODE_NOT_VALID => 'code不合法',
        self::WECHAT_REFRESH_TOKEN_NOT_VALID => 'refresh_token不合法',
        self::SYS_CONFIG_NOT_FOUND => '配置文件未找到',
        self::API_REQUEST_NOT_VALID => '非法请求',
        self::APP_ID_NOT_VALID => 'APPID不合法',
        self::APP_TIME_NOT_VALID => '时间不合法',
        self::APP_NONCE_NOT_VALID => '随机串不合法',
        self::API_UNKNOWN_ERROR => '未知错误',
        self::AGENT_UK_NOT_VALID_CODE => '代理唯一标识不合法',
        self::GAME_TYPE_NOT_VALID_CODE => '游戏类型不合法'
    ];

    public function __construct($code, $message = null)
    {
        if (is_null($message)) {
            $message = self::$error_msg[$code];
        }
        parent::__construct($message, $code);
    }
}
