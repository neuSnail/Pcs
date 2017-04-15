<?php
namespace Pcs\Common\Path;
class Path
{
    const CONFIG_PATH = 'resource/config/';
    private static $rootPath = '';

    public static function setRootPath($rootPath)
    {
        self::$rootPath = $rootPath;
    }

    private static function getRootPath()
    {
        if (empty(self::$rootPath)) {
            throw new \Exception('未设置rootPath');
        }

        return self::$rootPath;
    }

    public static function getConfigPath()
    {
        return self::getRootPath() . self::CONFIG_PATH;
    }
}