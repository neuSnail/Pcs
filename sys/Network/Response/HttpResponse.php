<?php
namespace Pcs\Network\Response;


use Pcs\Frame\Response\HtmlResponse;
use Pcs\Frame\Response\JsonResponse;

class HttpResponse extends Response
{
    private $response;

    public function __construct(\swoole_http_response $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function output($msg)
    {
        $this->response->header('charset', 'utf-8');
        $this->response->end($msg);
    }

    public function send($response)
    {
        if ($response instanceof JsonResponse) {

        }

        if ($response instanceof HtmlResponse) {

        }

        if (is_string($response)) {
            $this->output($response);
        }

        throw new \Exception("unexpected response type!");
    }
}