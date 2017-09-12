<?php
namespace Pcs\Network\Response;


use Pcs\Frame\Http\Response\Response;
use Pcs\Network\Response\Response as ResponseInterface;

class HttpResponse implements ResponseInterface
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

    public function output($content, $headers = null)
    {
        if ($headers !== null) {
            foreach ($headers as $header) {
                $this->response->header(key($header), current($header));
            }
        }
        $this->response->end($content);
    }

    public function send($response)
    {
        if ($response instanceof Response) {
            $this->output($response->getContent(), $response->getHeader());
            return;
        }


        throw new \Exception("unexpected response type!");
    }
}