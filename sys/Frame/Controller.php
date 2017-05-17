<?php
namespace Pcs\Frame;

use Pcs\Network\Context\Context;
use Pcs\Network\Request\Request;

abstract class Controller
{
    protected $context;
    protected $request;

    public function __construct(Context $context, Request $request)
    {
        $this->context = $context;
        $this->request = $request;
    }
}