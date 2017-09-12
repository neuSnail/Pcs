<?php
namespace Pcs\Common\BootStrap;

use Pcs\Common\Config\Config;
use Pcs\Common\Path\Path;
use Pcs\Common\RunMode\RunMode;
use Pcs\Frame\Db\SqlMap;

class BootStrap
{
    private $rootPath;

    public function boot($rootPath)
    {
        $this->rootPath = $rootPath;
        $this->PathInit();
        $this->RunModeInit();
        $this->ConfigInit();
        $this->SqlMapInit();
    }

    private function PathInit()
    {
        Path::setRootPath($this->rootPath);
    }

    private function RunModeInit()
    {
        RunMode::init();
    }

    private function ConfigInit()
    {
        Config::init();
    }

    private function SqlMapInit()
    {
        SqlMap::init();
    }
}