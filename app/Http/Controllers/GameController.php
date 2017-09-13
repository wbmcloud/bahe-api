<?php

namespace App\Http\Controllers;

use App\Common\Constant\CacheConst;
use App\Library\Redis;

class GameController extends Controller
{
    public function hotUpdateAction()
    {
        $md5 = Redis::get(CacheConst::HOT_UPDATE_FILE_MD5);

        if (empty($value)) {
            $path = config('services.file.path');
            $patch_name = config('services.file.patch_name');

            $file = file_get_contents($path . $patch_name);
            $md5 = md5($file);
        }
        return $this->jsonResponse([
            'md5' => $md5,
        ]);
    }

    public function downLoadAction()
    {
        $path = config('services.file.path');
        $patch_name = config('services.file.patch_name');

        return response()->download($path . $patch_name);
    }
}
