<?php
/**
 * Copyright © 链家网（北京）科技有限公司
 * User: wangdong01@lianjia.com
 * Date: 2016-11-2
 * Desc: apiclient 抽象类
 */

namespace App\Library;

use App\Common\Utils\SystemTool;
use App\Exceptions\BaheException;

class ApiClient
{

    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_GET  = 'GET';

    /**
     * @param        $service
     * @param        $path
     * @param        $params
     * @param string $method
     * @param array  $header
     * @param null   $data_callback
     * @return bool|mixed
     */
    public static function callApi($service, $path, $params, $method = self::HTTP_METHOD_POST, $header = array(), $data_callback = null)
    {
        $start_time = microtime(true);
        $api_config = [
            'server_name' => $service,
            'params'      => $params,
            'method'      => $method,
            'path'        => $path,
        ];
        try {
            $ret = self::handle($service, $path, $params, $method, $header);
        } catch (\Exception $e) {
            $log = self::_logMessage($api_config, $start_time, array(
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'data'  => array(),
            ));
            self::errorLog($log);

            return false;
        }
        $log              = self::_logMessage($api_config, $start_time, $ret);
        $result_conf      = self::config[$service]['result'];
        $error_code_field = self::_resolveData($result_conf['error_code_field'], $ret);
        if ($error_code_field != $result_conf['error_code_success']) {
            self::errorLog($log);
            if (is_callable($data_callback)) {
                call_user_func($data_callback, $ret);
            }

            return false;
        }
        self::log($log);
        if (is_callable($data_callback)) {
            call_user_func($data_callback, $ret);
        }
        $data = self::_resolveData($result_conf['error_data_field'], $ret);

        return $data;
    }

    //解析结果配置格式
    protected static function _resolveData($field_str, $data)
    {
        $error_code_field_array = explode('/', $field_str);
        $item                   = $data;
        foreach ($error_code_field_array as $value) {
            $item = $item[$value];
        }

        return $item;
    }

    /**
     * @param        $service_name
     * @param        $path
     * @param        $params
     * @param string $method
     * @param        $header
     * @return mixed
     * @throws BaheException
     */
    public static function handle($service_name, $path, $params, $method = self::HTTP_METHOD_POST, $header)
    {
        $conf = config('services.' . $service_name);
        if (!$conf) {
            throw new BaheException(BaheException::API_CLIENT_MISS_CONFIG);
        }
        if ($conf['is_sign']) {
            $params['token'] = self::getSignature($params, $conf['secret']);
        }
        $url = self::getFullUrl($conf, $path);

        $ch  = curl_init();
        self::setCurlOpt($ch, $conf, $url, $params, $method, $header);
        $num   = 0;
        $retry = $conf['retry'];
        do {
            $num++;
            $api_ret = curl_exec($ch);
            if ($retry > 0 && curl_errno($ch) != CURLE_COULDNT_CONNECT) { // 只有连接超时可以重试
                $retry = 0;
            }
        } while ($api_ret === false && $num <= $retry);
        if (curl_errno($ch)) {
            throw new BaheException(curl_errno($ch), curl_error($ch));
        }
        $ret = json_decode($api_ret, true);
        if (!is_array($ret)) {
            throw new BaheException(BaheException::API_CLIENT_RETURN_NOT_JSON);
        }
        if ($ch !== null) {
            curl_close($ch);
        }

        return $ret;
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
            if ($k == 'token') {
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
     * 获取URL
     * @param $config
     * @param $path
     * @return string
     */
    protected static function getFullUrl($config, $path)
    {
        return $config['schema'] . '://' . $config['host'] . ':' . $config['port'] . $path;
    }

    /**
     * @param $ch
     * @param $server_config
     * @param $url
     * @param $params
     * @param $method
     * @param $header
     */
    protected static function setCurlOpt(&$ch, $server_config, $url, $params, $method, $header)
    {
        $params = is_array($params) ? http_build_query($params) : $params;
        if (isset($server_config['ssl']) && $server_config['ssl']) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $server_config['connect_timeout_ms']);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $server_config['read_timeout_ms']);
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if (strtoupper($method) === self::HTTP_METHOD_GET) {
            if ($params) {
                $separator = strpos($url, '?') ? '&' : '?';
                curl_setopt($ch, CURLOPT_URL, $url . $separator . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        } else {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        }
    }

    /**
     * 获取输出日志
     * @param $api_config
     * @param $start_time
     * @param $api_result
     * @return array
     */
    private static function _logMessage($api_config, $start_time, $api_result)
    {
        $message = array(
            'path'       => $api_config['path'],
            'get'        => strtolower($api_config['method']) == 'get' ? $api_config['params'] : '',
            'post'       => strtolower($api_config['method']) == 'post' ? $api_config['params'] : '',
            'ret'        => $api_result,
            'project'    => env('APP_NAME'),
            'module'     => $api_config['server_name'],
            'request_id' => self::getRequestId(),
            'consume'    => round(microtime(true) - $start_time, 3),
            'node'       => isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '',
            'memory'     => SystemTool::getMemoryUsage(),
        );

        return $message;
    }


    protected static function errorLog(array $msg)
    {
        BLogger::error($msg);
    }

    protected static function log(array $msg)
    {
        BLogger::info($msg);
    }

    protected static function getRequestId()
    {
        return BContext::getRequestId();
    }
}
