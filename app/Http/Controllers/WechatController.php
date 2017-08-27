<?php

namespace App\Http\Controllers;

use App\Common\Constant\WechatConst;
use App\Exceptions\BaheException;
use App\Library\Redis;
use App\Library\Wechat;

class WechatController extends Controller
{
    public function userInfoAction()
    {
        $code = app('request')->input('code');
        $open_id = app('request')->input('open_id');

        // 参数校验
        if (empty($code) && empty($open_id)) {
            throw new BaheException(BaheException::API_ARGS_NOT_VALID);
        }

        if (!empty($code)) {
            // 重新获取access_token
            $ret = Wechat::getAccessToken($code);
            if (isset($ret['errcode']) && !empty($ret['errcode'])) {
                throw new BaheException(BaheException::WECHAT_CODE_NOT_VALID);
            }
            Redis::set(WechatConst::APP_ACCESS_TOKEN . $ret['open_id'], $ret);
        } else {
            $ret = Redis::get(WechatConst::APP_ACCESS_TOKEN . $open_id);
        }

        // 获取用户信息
        $user_info = Wechat::getUserInfo($ret['access_token'], $ret['openid']);
        if (isset($user_info['errcode']) && !empty($user_info['errcode'])) {
            // 不合法的access_token，需要调用refresh_token进行刷新
            $ret = Wechat::refreshAccessToken($ret['access_token']);
            if (isset($user_info['errcode']) && !empty($user_info['errcode'])) {
                throw new BaheException(BaheException::WECHAT_REFRESH_TOKEN_NOT_VALID);
            }
            Redis::set(WechatConst::APP_ACCESS_TOKEN . $ret['open_id'], $ret);
        }
        $user_info = Wechat::getUserInfo($ret['access_token'], $ret['openid']);

        return $this->jsonResponse($user_info);
    }
}
