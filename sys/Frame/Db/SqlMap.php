<?php
namespace Pcs\Frame\Db;

use Pcs\Common\Config\ConfigLoader;
use Pcs\Common\Path\Path;

class SqlMap
{
    private static $map = [];

    public static function init()
    {
        self::readMap();
    }

    private static function readMap()
    {
        $mapPath = Path::getSqlPath();
        $loader = ConfigLoader::getInstance();
        self::$map = $loader->load($mapPath);
    }

    public static function getSql($key)
    {
        $targets = explode('.', $key);
        $configMap = self::$map;
        do {
            if (empty($key)) {
                break;
            }

            foreach ($targets as $target) {
                if (!isset($configMap[$target])) {
                    break 2;
                }

                $configMap = $configMap[$target];
            }

            return $configMap;

        } while (0);

        throw new \Exception('sql map ' . $key, ' not found!');
    }
}