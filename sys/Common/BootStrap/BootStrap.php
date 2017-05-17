<?php
namespace Pcs\Common\BootStrap;

use Pcs\Common\Config\Config;
use Pcs\Common\Path\Path;
use Pcs\Common\RunMode\RunMode;

class BootStrap
{

    public function boot($rootPath)
    {
        $this->PathInit($rootPath);
        $this->RunModeInit();
        $this->ConfigInit();
    }

    public function PathInit($rootPath)
    {
        Path::setRootPath($rootPath);
    }

    public function RunModeInit()
    {
        RunMode::init();
    }

    public function ConfigInit()
    {
        Config::init();
    }
}