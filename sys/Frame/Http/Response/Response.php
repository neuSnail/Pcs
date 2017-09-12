<?php
namespace Pcs\Frame\Http\Response;

abstract class Response
{
    protected $header=[];
    protected $content;

    public function getHeader()
    {
        return $this->header;
    }

    public function getContent()
    {
        return $this->content;
    }
}

