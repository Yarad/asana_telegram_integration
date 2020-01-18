<?php

include_once "cDB.php";

class DAO
{
    private static $instances = [];

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$instances[static::class])) {
            self::$instances[static::class] = new static();
        }
        return self::$instances[static::class];
    }
}