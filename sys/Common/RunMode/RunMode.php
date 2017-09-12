<?php
namespace Pcs\Common\RunMode;

class RunMode
{
    private static $default = 'online';
    private static $runMode = '';
    private static $map = ['online', 'dev','qa'];

    public static function init()
    {
        self::detect();
    }

    private static function detect()
    {
        $runMode = get_cfg_var('pcs.RUN_MODE');
        if (!in_array($runMode, self::$map)) {
            self::$runMode = self::$default;
        } else {
            self::$runMode = $runMode;
        }
    }

    public static function getRunMode()
    {
        if (empty(self::$runMode)) {
            self::detect();
        }
        return self::$runMode;
    }
}