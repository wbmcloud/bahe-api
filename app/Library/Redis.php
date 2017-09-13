<?php

namespace App\Library;

class Redis
{
    public static function get($key)
    {
        $key = self::getKey($key);

        return unserialize(app('redis')->get($key));
    }

    public static function set($key, $value, $expire = null)
    {
        $key = self::getKey($key);

        if (is_array($value)) {
            $value = serialize($value);
        }

        if (!empty($expire)) {
            app('redis')->setex($key, $expire, $value);
        } else {
            app('redis')->set($key, $value);
        }
    }

    private static function getKey($key)
    {
        return env('CACHE_PREFIX', 'laravel') . ':' . $key;
    }
}