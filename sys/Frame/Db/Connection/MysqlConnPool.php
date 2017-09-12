<?php
namespace Pcs\Frame\Db\Connection;

use Pcs\Common\Config\Config;

class MysqlConnPool
{
    private static $pool = [];
    private static $connNum;

    public static function init()
    {
        $config = Config::get('mysql');
        $connNum = $config['mysql_conn_pool_num'];
        self::$connNum = $connNum;
        for ($i = 0; $i < $connNum; $i++) {
            $conn = new \mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
            self::$pool[] = $conn;
        }

    }

    public static function getConnection()
    {
        /*$connKey = rand(0, self::$connNum - 1);
        return self::$pool[$connKey];*/
        $config = Config::get('mysql');
        $conn = new \mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
        return $conn;
    }

}