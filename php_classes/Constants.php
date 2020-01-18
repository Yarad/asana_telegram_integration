<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 19:46
 */
class Constants
{
    private static $DEPLOYMENT_CONFIG = [];

    public static function init()
    {
        self::$DEPLOYMENT_CONFIG = parse_ini_file(__DIR__ . '/../deployment_config.ini');
    }

    public static function get($valueName)
    {
        if (isset(self::$$valueName)) {
            return self::$$valueName;
        } elseif (isset(self::$DEPLOYMENT_CONFIG[$valueName])) {
            return self::$DEPLOYMENT_CONFIG[$valueName];
        } else {
            throw new Exception('Field is not defined');
        }
    }

    public static function Log($logStr)
    {
        if (!is_string($logStr)) {
            $logStr = var_export($logStr, true);
        }
        file_put_contents( '../log.html', '<pre>' . $logStr . '</pre>', FILE_APPEND);
    }
}

Constants::init();