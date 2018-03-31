<?php

namespace App\Http\Controllers;

use App\Common\Constant\CacheConst;
use App\Common\Utils\SystemTool;
use App\Library\Redis;

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
}
