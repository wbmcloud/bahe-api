<?php

namespace App\Http\Controllers;

use App\Common\Constant\CacheConst;
use App\Common\Utils\SystemTool;
use App\Exceptions\BaheException;
use App\Library\Redis;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    public function jwtAction()
    {
        // 校验签名
        $params['app_id'] = app('request')->input('app_id');
        $params['time'] = app('request')->input('time');
        $params['nonce'] = app('request')->input('nonce');
        $params['sign'] = app('request')->input('sign');

        $client_config = config('services.client');
        // 校验app_id的合法性
        if (!in_array($params['app_id'], array_keys($client_config))) {
            throw new BaheException(BaheException::APP_ID_NOT_VALID);
        }

        // 防止重放攻击
        $value = Redis::get(CacheConst::JWT_NONCE);
        if (!empty($value) && ($params['nonce'] == $value)) {
            throw new BaheException(BaheException::APP_NONCE_NOT_VALID);
        }
        Redis::set(CacheConst::JWT_NONCE, $params['nonce'],
            CacheConst::$cache_alive[CacheConst::JWT_NONCE]);

        // 校验签名
        $app_secret = $client_config[$params['app_id']]['app_secret'];
        if (empty($app_secret)) {
            throw new BaheException(BaheException::SYS_CONFIG_NOT_FOUND);
        }
        $sign = SystemTool::getSignature($params, $app_secret);
        if ($params['sign'] != $sign) {
            throw new BaheException(BaheException::API_SIG_NOT_VALID);
        }

        // 生成token
        $key = env('JWT_SECRET');

        $token = array(
            "iss" => "account_center",
            "aud" => "game_client",
            "iat" => time(),
            "nbf" => time(),
            "exp" => time() + 86400,
            "client" => [
                'app_id' => $params['app_id'],
            ]
        );

        return $this->jsonResponse([
            'jwt' => JWT::encode($token, $key)
        ]);
    }


}
