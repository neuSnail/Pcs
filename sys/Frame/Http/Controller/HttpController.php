<?php
namespace Pcs\Frame\Http\Controller;

use Pcs\Common\Config\Config;
use Pcs\Common\Path\Path;
use Pcs\Frame\Controller;
use Pcs\Frame\Http\Response\HtmlResponse;
use Pcs\Frame\Http\Response\JsonResponse;
use Pcs\Frame\Http\Response\StringResponse;
use Pcs\Frame\Http\View\View;
use Pcs\Network\Request\HttpRequest;

/**
 * @property HttpRequest $request
 */
class HttpController extends Controller
{
    public function output($content)
    {
        return new StringResponse($content);
    }

    public function view($tpl, $vars = null)
    {
        $srcPath = Path::getSrcPath();
        $viewPath = $srcPath . '/' . $this->request->getModule() . '/View/' . $tpl;
        $pathInfo = pathinfo($viewPath);
        if (!$pathInfo['extension']) {
            $extension = Config::get('config.default_view_type');
            $viewPath = $viewPath . '.' . $extension;
        }

        if (!file_exists($viewPath)) {
            throw new \Exception('tpl not found at path: ' . $viewPath);
        }

        $view = new View($viewPath, $vars);
        $content = $view->getContent();
        return new HtmlResponse($content);

    }

    public function jsonResponse($status = 0, $data = null, $msg = '')
    {
        $responseArray = [
            'status' => $status,
            'data' => $data,
            'msg' => $msg
        ];

        return new JsonResponse($responseArray);
    }

    public function post($key = null)
    {
        return $this->request->get($key);
    }

    public function get($key = null)
    {
        return $this->request->get(null);
    }

    public function getServer()
    {
        return $this->request->getServer();
    }
}