<?php

namespace App\Http\Controllers;

use Verdant\XML2Array;

class GateController extends Controller
{
    public function serverListAction()
    {
        $xml = file_get_contents(realpath(__DIR__ . '/../../Library/ext/servers.xml'));
        $config = array(
            'attributesKey' => 'attributes',
            'cdataKey'      => 'cdata',
            'valueKey'      => 'value',
            'useNamespaces' => true,
        );
        $arr = XML2Array::createArray($xml, $config);
        return $arr;
    }
}
