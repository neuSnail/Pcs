<?php
namespace Pcs\Network\Request;

use Pcs\Coroutine\Task;
use Pcs\Frame\Http\Exception\PcsExceptionHandler;
use Pcs\Frame\Http\Router\Router;
use Pcs\Network\Context\Context;
use Pcs\Network\Response\HttpResponse;

class RequestHandler
{

    private $request;
    private $response;
    private $context;
    private $router;


    public function __construct(\swoole_http_request $request, \swoole_http_response $response)
    {
        $this->request = new HttpRequest($request);
        $this->response = new HttpResponse($response);
    }

    public function handle()
    {
        $this->context = new Context($this->request, $this->response, $this->getFd());
        $this->router = new Router($this->request);

        try {
            if (false === $this->router->parse()) {
                $this->response->output('');
                return;
            }
            $coroutine = $this->doRun();
            $task = new Task($coroutine, $this->context);
            $task->run();
        } catch (\Exception $e) {
            PcsExceptionHandler::handle($e, $this->response);
        }

    }

    private function getFd()
    {
        return spl_object_hash($this);
    }

    private function doRun()
    {
        $ret = (yield $this->dispatch());
        yield $this->response->send($ret);
    }

    private function dispatch()
    {
        $url = $this->router->getUrl();
        $module = $url['module'];
        $controller = $url['controller'];
        $action = $url['action'];
        $this->request->setModule($module);

        $controller = '\\App\\' . $module . '\\Controller\\' . $controller . 'Controller';
        if (!class_exists($controller)) {
            throw new \Exception('class ' . $controller . 'Controller not found');
        }

        $controller = new $controller($this->context, $this->request);

        if (!is_callable([$controller, $action])) {
            throw new \Exception('method ' . $action . ' is not callable');
        }

        yield $controller->$action();
    }

}