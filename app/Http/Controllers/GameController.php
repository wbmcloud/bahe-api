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
        $issue = Redis::get(CacheConst::HOT_UPDATE_VERSION_ISSUE);
        $md5 = Redis::get(CacheConst::HOT_UPDATE_FILE_MD5 . $issue);

        return $this->jsonResponse([
            'file_name' => sprintf(config('services.file.patch_name'), $issue),
            'issue' => $issue,
            'md5' => $md5,
        ]);
    }

    public function downLoadAction()
    {
        $issue = app('request')->input('issue');

        $path = config('services.file.path');
        $patch_name = config('services.file.patch_name');
        $patch_name = sprintf($patch_name, $issue);

        return response()->download($path . $patch_name);
    }
}
