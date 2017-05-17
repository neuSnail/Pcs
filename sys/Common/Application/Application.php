<?php
namespace Pcs\Common\Application;

use Pcs\Server\Http\HttpServer;

class Application
{
    public function startHttpServer(HttpServer $server)
    {
        $server->start();
    }
}