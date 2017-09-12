<?php
namespace Pcs\Frame\Http\Router;


use Pcs\Common\Config\Config;
use Pcs\Common\Path\Path;
use Pcs\Network\Request\HttpRequest;

class Router
{
    private $request;
    private $module;
    private $controller;
    private $action;

    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    public function parse()
    {
        $server = $this->request->getServer();

        if (preg_match('/\.ico$/i', $server['request_uri'])) {
            return false;
        }

        $requestArr = explode('/', $server['request_uri']);
        $this->module = ucfirst($requestArr[1]);
        if (empty($this->module)) {
            $this->setDefault();
        } else {
            $this->controller = ucfirst($requestArr[2]);
            $this->action = $requestArr[3];
        }


        $this->routeCheck();
        return true;
    }

    private function routeCheck()
    {
        $srcPath = Path::getSrcPath();
        if (!file_exists(
            $srcPath . '/' . $this->module . '/Controller/' . $this->controller . 'Controller.php'
        )
        ) {
            throw new \Exception('request file ' . $this->controller . 'Controller.php not found');
        }


    }

    private function setDefault()
    {
        $default = Config::get('config.default_route');
        $this->module = $default['module'];
        $this->controller = $default['controller'];
        $this->action = $default['action'];
    }

    public function getUrl()
    {
        return [
            'module' => $this->module,
            'controller' => $this->controller,
            'action' => $this->action
        ];
    }
}