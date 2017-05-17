<?php
namespace Pcs\Server\Http;

use Pcs\Server\Server;
use Pcs\Network\Request\RequestHandler;

class HttpServer implements Server
{
    private $swooleHttpServer;

    public function __construct(\swoole_http_server $swooleHttpServer)
    {
        $this->swooleHttpServer = $swooleHttpServer;
    }

    public function start()
    {
        $this->swooleHttpServer->on('start', [$this, 'onStart']);
        $this->swooleHttpServer->on('shutdown', [$this, 'onShutdown']);

        $this->swooleHttpServer->on('workerStart', [$this, 'onWorkerStart']);
        $this->swooleHttpServer->on('workerStop', [$this, 'onWorkerStop']);
        $this->swooleHttpServer->on('workerError', [$this, 'onWorkerError']);

        $this->swooleHttpServer->on('request', [$this, 'onRequest']);

        $this->swooleHttpServer->start();
    }

    public function stop()
    {

    }

    public function reload()
    {

    }

    public function onStart()
    {
        echo "server start \n";
    }

    public function onShutdown()
    {
        echo "server shutdown \n";
    }

    public function onWorkerStart(\swoole_http_server $server, $workerId)
    {
        echo "worker start id : $workerId \n";
    }

    public function onWorkerStop()
    {

    }

    public function onWorkerError()
    {

    }

    public function onRequest(\swoole_http_request $request, \swoole_http_response $response)
    {
         $requestHandler = new RequestHandler($request, $response);
         $requestHandler->handle();
    }
}