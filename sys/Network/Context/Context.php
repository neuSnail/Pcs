<?php
namespace Pcs\Network\Context;

class Context
{
    private $request;
    private $response;
    private $fd;

    public function __construct($request, $response, $fd)
    {
        $this->request = $request;
        $this->response = $response;
        $this->fd = $fd;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getFd()
    {
        return $this->fd;
    }

}