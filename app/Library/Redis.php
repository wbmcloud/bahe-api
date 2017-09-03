<?php

namespace App\Library;

class Redis
{
    public static function get($key)
    {
        return json_decode(app('redis')->get($key), true);
    }

    public static function set($key, $value, $expire = null)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        if (!empty($expire)) {
            app('redis')->setex($key, $expire, $value);
        } else {
            app('redis')->set($key, $value);
        }
    }
}