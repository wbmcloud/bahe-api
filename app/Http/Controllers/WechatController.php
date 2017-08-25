<?php

namespace App\Http\Controllers;

use App\Library\Wechat;

class WechatController extends Controller
{
    public function userInfoAction()
    {
        $code = app('request')->input('code');

        $ret          = Wechat::getAccessToken($code);
        $access_token = $ret['access_token'];
        $open_id      = $ret['openid'];

        return $this->jsonResponse(Wechat::getUserInfo($access_token, $open_id));
    }
}
