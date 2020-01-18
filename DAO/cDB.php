<?php
include_once __DIR__ . "/../php_classes/Constants.php";

/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.10.2017
 * Time: 19:51
 */
class cDB
{
    /** @var PDO */
    private static $dbLink;

    private static $dbPostfix;

    private function __construct()
    {
        //ограничение на длину названия БД - 8
        $dbName = Constants::get('DB_NAME');
        $dsn = "mysql:host=" . Constants::get('DB_HOST_NAME') . ";dbname=" . $dbName . ";charset=utf8";
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        self::$dbLink = new PDO(
            $dsn,
            Constants::get('DB_LOGIN'),
            Constants::get('DB_PASSWORD'), $opt);
        if (self::$dbLink != null) {
            self::$dbLink->query("SET NAMES utf8");
            self::$dbLink->query("SET CHARACTER SET utf8");
            self::$dbLink->query("SET character_set_client = utf8");
            self::$dbLink->query("SET character_set_connection = utf8");
            self::$dbLink->query("SET character_set_results = utf8");
        }
    }

    public static function getInstance()
    {
        if (empty(self::$dbLink)) {
            new cDB();
            if (empty(self::$dbLink)) {
                throw new \Exception('Cannot connect to DB');
            }
        }
        return self::$dbLink;
    }
}