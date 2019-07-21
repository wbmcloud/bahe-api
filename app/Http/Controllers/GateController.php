<?php

namespace App\Http\Controllers;

use App\Common\Constant\CommonConst;
use App\Exceptions\BaheException;
use App\Models\GameServer;
use App\Models\GatewayServer;

class GateController extends Controller
{
    public function serverListAction()
    {
        //$token_info = SystemTool::getTokenInfo(app('request')->header('JWT'));
        //$app_id = $token_info->client->app_id;
        $app_id = 'faiejfjkn14jsk945k';

        $game_server = GameServer::where([
            'app_id' => $app_id
        ])->first()->toArray();

        if (empty($game_server)) {
            throw new BaheException(BaheException::GAME_TYPE_NOT_VALID_CODE);
        }
        $serverlist = GatewayServer::where([
            'game_type' => $game_server['game_type'],
            'is_del' => CommonConst::STATUS_DISABLE
        ])->get()->toArray();

        return $this->jsonResponse([
            'serverlist' => $serverlist
        ]);
    }
}
