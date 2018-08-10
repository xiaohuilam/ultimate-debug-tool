<?php
namespace Xiaohuilam\UltDebug;

class Store
{
    public static function get($key)
    {
        return new StoreGet($key);
    }

    public static function set($key, $value)
    {
        return new StoreSet($key, $value);
    }
}
