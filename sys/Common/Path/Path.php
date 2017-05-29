<?php
namespace Pcs\Common\Path;

use Pcs\Common\RunMode\RunMode;

class Path
{
    const CONFIG_PATH = '/resource/config';
    const SRC_PATH = '/src';
    private static $rootPath = '';

    public static function setRootPath($rootPath)
    {
        self::$rootPath = $rootPath;
    }


    public static function getRootPath()
    {
        if (empty(self::$rootPath)) {
            throw new \Exception('未设置rootPath');
        }

        return self::$rootPath;
    }

    public static function getConfigPath()
    {
        return self::getRootPath() . self::CONFIG_PATH . '/' . RunMode::getRunMode();
    }

    public static function getSrcPath()
    {
        return self::getRootPath() . self::SRC_PATH;
    }
}