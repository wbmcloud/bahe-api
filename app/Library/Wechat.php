<?php

namespace App\Library;

use App\Common\Constant\CommonConst;
use App\Common\Utils\SystemTool;
use App\Exceptions\BaheException;

class Wechat
{
    /**
     * 服务名称
     */
    const SERVICE_NAME = 'wechat';

    /**
     * 接口
     */
    const SNS_OAUTH2_ACCESS_TOKEN = '/sns/oauth2/access_token';
    const SNS_OAUTH2_REFRESH_TOKEN = '/sns/oauth2/refresh_token';
    const SNS_AUTH = '/sns/auth';
    const SNS_USERINFO = '/sns/userinfo';

    /**
     * @param $code
     * @param $app_id
     * @return bool|mixed
     */
    public static function getAccessToken($code, $app_id)
    {
        $config = SystemTool::getWxConfig($app_id);
        $params['appid'] = $config['app_id'];
        $params['secret'] = $config['app_secret'];
        $params['code'] = $code;
        $params['grant_type'] = 'authorization_code';

        return ApiClient::callApi(self::SERVICE_NAME, self::SNS_OAUTH2_ACCESS_TOKEN,
            $params, ApiClient::HTTP_METHOD_GET, [],
            'App\Library\Wechat::processResponse');
    }

    /**
     * @param $refresh_token
     * @return bool|mixed
     */
    public static function refreshAccessToken($refresh_token)
    {
        $params['appid'] = config('services.wechat.app_id');
        $params['refresh_token'] = $refresh_token;
        $params['grant_type'] = 'refresh_token';

        return ApiClient::callApi(self::SERVICE_NAME, self::SNS_OAUTH2_REFRESH_TOKEN,
            $params, ApiClient::HTTP_METHOD_GET, [],
            'App\Library\Wechat::processResponse');
    }

    /**
     * @param $access_token
     * @param $open_id
     * @return bool|mixed
     */
    public static function getUserInfo($access_token, $open_id)
    {
        $params['access_token'] = $access_token;
        $params['openid'] = $open_id;

        return ApiClient::callApi(self::SERVICE_NAME, self::SNS_USERINFO,
            $params, ApiClient::HTTP_METHOD_GET, [],
            'App\Library\Wechat::processResponse');
    }

    /**
     * @param $access_token
     * @param $open_id
     * @return bool|mixed
     */
    public static function isTokenValid($access_token, $open_id)
    {
        $params['access_token'] = $access_token;
        $params['openid'] = $open_id;

        return ApiClient::callApi(self::SERVICE_NAME, self::SNS_AUTH,
            $params, ApiClient::HTTP_METHOD_GET, [],
            'App\Library\Wechat::processResponse');
    }

    /**
     * @param $ret
     * @return mixed
     * @throws BaheException
     */
    public static function processResponse($ret)
    {
        if (empty($ret)) {
            throw new BaheException(BaheException::API_CLIENT_CALL_FAIL);
        }

        return $ret;
    }
}