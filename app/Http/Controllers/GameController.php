<?php

namespace App\Http\Controllers;

use App\Common\Constant\CacheConst;
use App\Library\Redis;

class GameController extends Controller
{
    public function hotUpdateAction()
    {
        /*$md5 = Redis::get(CacheConst::HOT_UPDATE_FILE_MD5);

        if (empty($md5)) {
            $path = config('services.file.path');
            $patch_name = config('services.file.patch_name');

            $file = file_get_contents($path . $patch_name);
            $md5 = md5($file);
        }*/
        $client_ver = app('request')->input('ver');
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

        $issues = array_map(function ($issue) {
            return [
                'name' => $issue['name'],
                'md5' => $issue['md5'],
                'size' => $issue['size'],
                'version' => $issue['version'],
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
