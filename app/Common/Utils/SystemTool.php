<?php

namespace App\Common\Utils;

use App\Common\Constant\CommonConst;
use App\Exceptions\BaheException;
use Firebase\JWT\JWT;

class SystemTool
{
    /**
     * 获取内存使用情况
     * @param string $unit
     * @return string
     */
    public static function getMemoryUsage($unit = 'MB')
    {
        switch (strtoupper($unit)) {
            case 'B':
                $use = memory_get_usage(true);
                break;
            case 'KB':
                $use = memory_get_usage(true) / 1024;
                break;
            case 'GB':
                $use = memory_get_usage(true) / 1024 / 1024 / 1024;
                break;
            default:
                $use = memory_get_usage(true) / 1024 / 1024;
                break;
        }

        return $use . $unit;
    }

    /**
     * 签名生成算法
     * @param array  $params
     * @param string $secret 签名
     * @return string
     */
    public static function getSignature($params, $secret)
    {
        $str = '';
        ksort($params);
        foreach ($params as $k => $v) {
            if ($k == 'sign') {
                continue;
            }
            $serialize = $v;
            if (is_array($v)) {
                ksort($v);
                $serialize = implode(',', array_keys($v));
            }
            $str .= "{$k}={$serialize}&";
        }
        $str .= $secret;

        return md5($str);
    }

    /**
     * @param $token
     * @return mixed
     * @throws BaheException
     */
    public static function getTokenInfo($token)
    {
        static $decode_map;

        if (!empty($decode_map) && isset($decode_map[$token])) {
            return $decode_map[$token];
        }

        $key = env('JWT_SECRET');
        try {
            $decode_map[$token] = JWT::decode($token, $key, array('HS256'));
        } catch (\Exception $e) {
            throw new BaheException(BaheException::JWT_NOT_VALID);
        }

        return $decode_map[$token];
    }

    /**
     * @param $app_id
     * @return mixed
     */
    public static function getWxConfig($app_id)
    {
        return config('services.client.' . $app_id . '.wx');
    }


    public static function isLocalDownload($app_id)
    {
        return in_array($app_id, array_keys(CommonConst::$local_download_app));
    }
}