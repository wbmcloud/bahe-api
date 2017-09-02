<?php

namespace App\Common\Utils;

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
}