<?php

namespace App\Library;

class Redis
{
    public static function get($key)
    {
        $key = self::getKey($key);

        return self::unserialize(app('redis')->get($key));
    }

    public static function set($key, $value, $expire = null)
    {
        $key = self::getKey($key);

        $value = self::serialize($value);

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

    /**
     * Serialize the value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function serialize($value)
    {
        return is_numeric($value) ? $value : serialize($value);
    }

    /**
     * Unserialize the value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function unserialize($value)
    {
        return is_numeric($value) ? $value : unserialize($value);
    }
}