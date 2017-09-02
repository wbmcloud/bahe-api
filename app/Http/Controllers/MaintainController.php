<?php

namespace App\Http\Controllers;

use App\Common\Constant\CacheConst;
use App\Common\Constant\MaintainConst;

class MaintainController extends Controller
{
    public function serverAction()
    {
        $ret = [
            'is_running' => true,
            'tips' => '',
        ];

        $value = app('redis')->get(CacheConst::SERVER_DOWN_MAINTAIN);
        if (empty($value)) {
            return $this->jsonResponse($ret);
        }

        return $this->jsonResponse([
            'is_running' => false,
            'tips' => MaintainConst::SERVER_DOWN_MAINTAIN_TEXT,
        ]);
    }
}
