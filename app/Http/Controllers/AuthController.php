<?php

namespace App\Http\Controllers;

use App\Common\Utils\SystemTool;
use App\Exceptions\BaheException;
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

        $app_secret = config('services.client.cy.app_secret');
        if (empty($app_secret)) {
            throw new BaheException(BaheException::SYS_CONFIG_NOT_FOUND);
        }
        $sign = SystemTool::getSignature($params, $app_secret);
        if ($params['sign'] != $sign) {
            throw new BaheException(BaheException::API_SIG_NOT_VALID);
        }

        $key = env('JWT_SECRET');

        $token = array(
            "iss" => "account_center",
            "aud" => "game_client",
            "iat" => time(),
            "nbf" => time(),
        );

        return $this->jsonResponse([
            'jwt' => JWT::encode($token, $key)
        ]);
    }
}
