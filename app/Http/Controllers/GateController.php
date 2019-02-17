<?php

namespace App\Http\Controllers;

use App\Common\Constant\CacheConst;
use App\Common\Utils\SystemTool;
use App\Library\Redis;

class GateController extends Controller
{
    public function serverListAction()
    {
        $token_info = SystemTool::getTokenInfo(app('request')->header('JWT'));
        $app_id = $token_info->client->app_id;

        /*$env = env('APP_ENV');
        $server_path = $env . '.client.' . $app_id . '.serverlist';
        $serverlist = config($server_path);*/
        $serverlist = Redis::hget(CacheConst::GAME_SERVER_LIST_CONFIG, $app_id, false);

        return $this->jsonResponse([
            'serverlist' => $serverlist
        ]);
    }
}
