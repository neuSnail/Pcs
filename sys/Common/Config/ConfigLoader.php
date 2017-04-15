<?php
namespace Pcs\Common\Config;

use Pcs\Common\Path\Path;
use Pcs\Common\Traits\Singleton;

class ConfigLoader
{
    use Singleton;

    public function load()
    {
        $configPath = Path::getConfigPath();
    }
}