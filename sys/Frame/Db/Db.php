<?php
namespace Pcs\Frame\Db;

use Pcs\Common\Security\Security;
use Pcs\Frame\Db\Connection\MysqlConnPool;
use Pcs\Common\Config\Config;
use Pcs\Frame\Db\Mysqli\Mysqli;

class Db
{

    public static function query($key, $params = null)
    {
        $sql = SqlMap::getSql($key);
        if ($params != null) {
            $sql = self::bind($sql, $params);
        }

       yield self::doQuery($sql);
    }

    private static function bind($sql, $params)
    {
        foreach ($params as $key => $value) {
            $search = '#' . $key;
            $replace = Security::sqlFilter($value);
            $sql = str_replace($search, $replace, $sql);
        }

        return $sql;
    }

    private static function doQuery($sql)
    {
        $driver = new Mysqli();
        yield $driver->query($sql);
    }

}
