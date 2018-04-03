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

    public static function hmget($hash, array $keys, $is_serialize = true)
    {
        return array_map(function ($issue) use ($is_serialize) {
            if ($is_serialize) {
                return self::unserialize($issue);
            } else {
                return json_decode($issue, true);
            }
        }, app('redis')->hmget(self::getKey($hash), $keys));;
    }

    public static function hget($hash, $key, $is_serialize = true)
    {
        if ($is_serialize) {
            $issues = app('redis')->hget(self::getKey($hash), $key);
        } else {
            $issues = json_decode(app('redis')->hget(self::getKey($hash), $key), true);
        }
        if (empty($issues)) {
            return [];
        }
        return array_map(function ($issue) use ($is_serialize) {
            if ($is_serialize) {
                return self::unserialize($issue);
            }
            return $issue;
        }, $issues);;
    }
}