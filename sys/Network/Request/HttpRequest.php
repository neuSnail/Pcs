<?php
namespace Pcs\Network\Request;

use Pcs\Common\Security\Security;
use Pcs\Frame\Controller;

class HttpRequest extends Request
{
    private $swooleHttpRequest;
    private $module;
    private $controller;
    private $action;

    public function __construct(\swoole_http_request $request)
    {
        $this->swooleHttpRequest = $request;
    }

    public function parse()
    {
        $server = $this->swooleHttpRequest->server;
        $requestArr = explode('/', $server['request_uri']);
        $this->module = $requestArr[0];
        $this->controller = $requestArr[1];
        $this->action = $requestArr[2];
        //var_dump($requestArr);
    }

    public function post($key = null)
    {
        $post = $this->swooleHttpRequest->post;
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
}