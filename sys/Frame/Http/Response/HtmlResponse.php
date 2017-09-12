<?php
namespace Pcs\Frame\Http\Response;

class HtmlResponse extends Response
{
    public function __construct($content)
    {
        $this->content = $content;
        $this->createHeader();
    }

    private function createHeader()
    {
        $this->header[] = ['Content-type', 'text/html'];
    }
}