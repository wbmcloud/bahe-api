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

        // 校验app_id的合法性
        if ($params['app_id'] != config('services.client.cy.app_id')) {
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
        $app_secret = config('services.client.cy.app_secret');
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
        );

        return $this->jsonResponse([
            'jwt' => JWT::encode($token, $key)
        ]);
    }
}
