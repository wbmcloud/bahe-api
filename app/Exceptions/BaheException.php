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

    const JWT_NOT_EXIST = 200100;
    const JWT_NOT_VALID = 200101;



    public static $error_msg = [
        self::API_CLIENT_MISS_CONFIG => '配置文件不存在',
        self::API_CLIENT_RETURN_NOT_JSON => '返回的非json数据',
        self::API_CLIENT_CALL_FAIL => '接口调用失败',
        self::API_NOT_FOUND => 'API不存在',
        self::JWT_NOT_VALID => 'JWT不合法',
    ];

    public function __construct($code, $message = null)
    {
        if (is_null($message)) {
            $message = self::$error_msg[$code];
        }
        parent::__construct($message, $code);
    }
}
