<?php

namespace App\Http\Controllers;

use App\Common\Constant\CacheConst;
use App\Common\Constant\GameConst;
use App\Library\Redis;
use Qcloud\Cos\Client;

class StorageController extends Controller
{
    public function uploadAction()
    {
        $platform = app('request')->input('platform');
        $bucket = 'bahe-1251456605';
        $key = 'president/patch/'. env('APP_ENV') . '/' . GameConst::$client_type_text_map[$platform] . '/' . $_FILES['file']['name'];

        $cosClient = new Client([
            'region'      => 'ap-beijing-1',
            'credentials' => [
                'secretId'  => 'AKID5zd3D7GDTTDhvtu3uWt0rhIm7E5gf92g',
                'secretKey' => 'WqxrX1HK9pIXYMxKUSDm6Azh9LIzQbVA'
            ]
        ]);

        $cosClient->putObject([
            'Bucket' => $bucket,
            'Key'    => $key,
            'Body'   => fopen($_FILES['file']['tmp_name'], 'rb')
        ]);

        return $this->jsonResponse();
    }

    public function versionAction()
    {
        $platform = app('request')->input('platform');
        $app_id = app('request')->input('app_id');

        $content = file_get_contents($_FILES['file']['tmp_name']);

        $json = Redis::hget(CacheConst::GAME_UPDATE_PATCH_CONFIG, $app_id, false);
        $json[$platform] = json_decode($content, true);

        Redis::hset(CacheConst::GAME_UPDATE_PATCH_CONFIG, $app_id, json_encode($json, JSON_UNESCAPED_SLASHES));

        return $this->jsonResponse();
    }
}
