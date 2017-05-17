<?php
namespace Pcs\Common\Traits;
trait Singleton
{
    private static $instance = null;

    public static function getInstance()
    {
        return self::singleton();
    }

    private static function singleton()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}