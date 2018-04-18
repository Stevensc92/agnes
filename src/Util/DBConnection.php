<?php

namespace Agnes\Util;

class DBConnection
{
    private static $db;
    private static $config = [];

    public static function setConfig($config): void
    {
        self::$config   = array_merge(self::$config, $config);
        self::$db       = null;
    }

    public static function getInstance(): \PDO
    {
        if (!self::$db)
        {
            $dsn = 'mysql:dbname='.self::$config['db_name'].';host='.self::$config['db_host'];
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::MYSQL_ATTR_FOUND_ROWS => TRUE,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            );

            self::$db = new \PDO($dsn, self::$config['db_user'], self::$config['db_pass'], $options);
        }

        return self::$db;
    }
}

?>
