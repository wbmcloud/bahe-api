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

    const JWT_NOT_EXIST = 200100;
    const JWT_NOT_VALID = 200101;

    const WECHAT_CODE_NOT_VALID = 200200;
    const WECHAT_REFRESH_TOKEN_NOT_VALID = 200201;

    const SYS_CONFIG_NOT_FOUND = 200300;

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
    ];

    public function __construct($code, $message = null)
    {
        if (is_null($message)) {
            $message = self::$error_msg[$code];
        }
        parent::__construct($message, $code);
    }
}
