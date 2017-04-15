<?php
namespace Pcs\Common\Traits;
trait Singleton
{
    private static $instance = null;

    public function getInstance()
    {
        return self::singleton();
    }

    private function singleton()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}