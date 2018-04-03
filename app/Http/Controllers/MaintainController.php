<?php

namespace App\Http\Controllers;

use App\Common\Constant\CacheConst;
use App\Common\Constant\MaintainConst;
use App\Common\Utils\SystemTool;
use App\Library\Redis;

class MaintainController extends Controller
{
    public function serverAction()
    {
        $token_info = SystemTool::getTokenInfo(app('request')->header('JWT'));
        $app_id = $token_info->client->app_id;

        $ret = [
            'is_running' => true,
            'tips' => '',
        ];

        $value = Redis::hget(CacheConst::SERVER_MAINTAIN_INFO, $app_id, false);

        if (empty($value)) {
            return $this->jsonResponse($ret);
        }

        return $this->jsonResponse([
            'is_running' => ($value['is_running'] == 1) ? true : false,
            'tips' => $value['content'],
        ]);
    }
}
