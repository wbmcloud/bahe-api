<?php

namespace App\Http\Controllers;

use App\Common\Constant\CacheConst;
use App\Common\Constant\CommonConst;
use App\Common\Constant\GameConst;
use App\Common\Utils\SystemTool;
use App\Exceptions\BaheException;
use App\Library\Redis;
use App\Models\PlayerBindAgent;
use App\User;

class GameController extends Controller
{
    public function hotUpdateAction()
    {
        $client_ver = app('request')->input('ver');
        // 解析token
        $token_info = SystemTool::getTokenInfo(app('request')->header('JWT'));

        if (!isset($token_info->client->app_id) || SystemTool::isLocalDownload($token_info->client->app_id)) {
            $version = Redis::get(CacheConst::HOT_UPDATE_ISSUE_VERSION);

            if ($version == false) {
                return $this->jsonResponse(['issues' => []]);
            }

            if (empty($client_ver)) {
                $issues = Redis::hmget(CacheConst::HOT_UPDATE_FILE_ISSUES, range(1, $version));
            } else {
                if ($client_ver < $version) {
                    // 获取所有的列表
                    $issues = Redis::hmget(CacheConst::HOT_UPDATE_FILE_ISSUES, range($client_ver + 1, $version));
                } else {
                    $issues = [];
                }
            }
        } else {
            // 获取当前app的所有更新配置
            $issues = Redis::hget(CacheConst::GAME_UPDATE_PATCH_CONFIG, $token_info->client->app_id, false);
            if ($client_ver < count($issues)) {
                $pos = array_search($client_ver, array_keys(array_column($issues, null, 'version')));
                if ($pos !== false) {
                    $issues = array_slice($issues, $pos + 1);
                }
            } else {
                $issues = [];
            }
        }

        $issues = array_map(function ($issue) use ($token_info) {
            return [
                'id' => isset($issue['id']) ? $issue['id'] : '',
                'name' => isset($issue['name']) ? $issue['name'] : '',
                'md5' => isset($issue['md5']) ? $issue['md5'] : '',
                'size' => isset($issue['size']) ? $issue['size'] : '',
                'version' => isset($issue['version']) ? $issue['version'] : '',
                'url' => isset($issue['url']) ? $issue['url'] : '',
            ];
        }, $issues);

        return $this->jsonResponse(['issues' => $issues]);
    }

    public function downLoadAction()
    {
        $ver = app('request')->input('ver');

        $path = config('services.file.path');
        $patch_name = config('services.file.patch_name');
        $patch_name = sprintf($patch_name, $ver);

        return response()->download($path . $patch_name);
    }

    public function bindAgentAction()
    {
        $agent_id = app('request')->input('agent_id');
        $player_id = app('request')->input('player_id');
        $type = app('request')->input('type');

        // 校验代理id的合法性
        $agent = User::where([
            'uk' => $agent_id
        ])->first();

        if (empty($agent)) {
            throw new BaheException(BaheException::AGENT_UK_NOT_VALID_CODE);
        }

        if (!in_array($type, GameConst::$game_type_map)) {
            $type = GameConst::GAME_TYPE_DDZ;
        }

        $player_bind_agent = PlayerBindAgent::where([
            'agent_id' => $agent_id,
            'player_id' => $player_id,
            'type' => $type,
            'status' => CommonConst::STATUS_ENABLE
        ])->first();

        if (!empty($player_bind_agent)) {
            return $this->jsonResponse([
                'is_bind' => true
            ]);
        }

        $player_bind_agent = new PlayerBindAgent();
        $player_bind_agent->agent_id = $agent_id;
        $player_bind_agent->player_id = $player_id;
        $player_bind_agent->type = $type;
        $player_bind_agent->save();

        return $this->jsonResponse([
            'agent_id' => strval($agent_id),
            'is_bind' => true
        ]);
    }

    public function bindStatusAction()
    {
        $player_id = app('request')->input('player_id');
        $type = app('request')->input('type');

        if (!in_array($type, GameConst::$game_type_map)) {
            $type = GameConst::GAME_TYPE_DDZ;
        }

        $player_bind_agent = PlayerBindAgent::where([
            'player_id' => $player_id,
            'type' => $type,
            'status' => CommonConst::STATUS_ENABLE
        ])->first();

        $ret = !empty($player_bind_agent) ? [
            'agent_id' => strval($player_bind_agent['agent_id']),
            'is_bind' => true
        ] : [
            'agent_id' => '',
            'is_bind' => false
        ];

        return $this->jsonResponse($ret);
    }
}
