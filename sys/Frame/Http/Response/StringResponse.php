<?php
namespace Pcs\Frame\Http\Response;


use Pcs\Common\Config\Config;

class StringResponse extends Response
{
    public function __construct($content)
    {
        $this->content = $this->createContent($content);
        $this->createHeader();
    }

    private function createContent($content)
    {
        if (is_string($content)) {
            return $content;
        }

        return print_r($content, true);
    }

    private function createHeader()
    {
        $charset = Config::get('config.output_charset');
        $this->header[] = ['Content-Type' => 'text/html'];
        $this->header[] = ['charset' => $charset];
    }
}