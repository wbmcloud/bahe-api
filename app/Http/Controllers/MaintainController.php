<?php

namespace App\Http\Controllers;

use App\Common\Constant\CacheConst;
use App\Common\Constant\MaintainConst;
use App\Library\Redis;

class MaintainController extends Controller
{
    public function serverAction()
    {
        $ret = [
            'is_running' => true,
            'tips' => '',
        ];

        $value = Redis::get(CacheConst::SERVER_MAINTAIN_INFO);
        
        if (empty($value)) {
            return $this->jsonResponse($ret);
        }

        return $this->jsonResponse([
            'is_running' => ($value['status'] == 1) ? false : true,
            'tips' => $value['content'],
        ]);
    }
}
