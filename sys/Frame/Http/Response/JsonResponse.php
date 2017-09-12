<?php
namespace Pcs\Frame\Http\Response;

class JsonResponse extends Response
{
    public function __construct($content)
    {
        $this->content = $this->createJson($content);
        $this->createHeader();
    }

    private function createJson($content)
    {
        return json_encode($content, JSON_UNESCAPED_UNICODE);
    }

    private function createHeader()
    {
        $this->header[] = ['Content-Type'=>'application/json'];
    }

}