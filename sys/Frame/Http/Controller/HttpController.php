<?php
namespace Pcs\Frame\Http\Controller;

use Pcs\Frame\Controller;
use Pcs\Network\Request\HttpRequest;

/**
 * @property HttpRequest $request
 */
class HttpController extends Controller
{
    public function output()
    {

    }

    public function view($path, $var)
    {

    }

    public function jsonResponse($status = 0, $data = null, $msg = '')
    {
        $responseArray = [
            'status' => $status,
            'data' => $data,
            'msg' => $msg
        ];
    }
}