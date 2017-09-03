<?php

namespace App\Http\Controllers;

use App\Library\BContext;
use App\Library\BLogger;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $ret;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->ret = [
            'code' => 0,
            'message' => '',
            'request_id' => BContext::getRequestId(),
            'data' => (object)null,
        ];
    }

    /**
     * @param null $data
     * @return string
     */
    protected function jsonResponse($data = null)
    {
        !empty($data) && is_array($data) && ($this->ret['data'] = $data);

        BLogger::info($this->ret);

        return response()->json($this->ret);
    }
}
