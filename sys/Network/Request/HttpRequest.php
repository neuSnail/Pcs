<?php
namespace Pcs\Network\Request;

use Pcs\Common\Security\Security;
use Pcs\Frame\Controller;

class HttpRequest implements Request
{
    private $swooleHttpRequest;
    private $module = null;

    public function __construct(\swoole_http_request $request)
    {
        $this->swooleHttpRequest = $request;
    }


    public function post($key = null)
    {
        $post = $this->swooleHttpRequest->post;
        if (empty($post)) return null;
        array_walk_recursive($post, [$this, 'xssFilter']);
        if (null === $key) {
            return $post;
        }

        if (!isset($post[$key])) {
            return null;
        }

        return $post[$key];
    }

    public function get($key = null)
    {
        $get = $this->swooleHttpRequest->get;
        if (empty($get)) return null;
        array_walk_recursive($get, [$this, 'xssFilter']);
        if (null === $key) {
            return $get;
        }

        if (!isset($get[$key])) {
            return null;
        }

        return $get[$key];
    }

    private function xssFilter(&$string)
    {
        $string = trim($string);
        // $string = strip_tags($string);
        $string = htmlspecialchars($string);
    }

    public function getSwooleRequest()
    {
        return $this->swooleHttpRequest;
    }

    public function getServer()
    {
        return $this->swooleHttpRequest->server;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function getModule()
    {
        return $this->module;
    }


}