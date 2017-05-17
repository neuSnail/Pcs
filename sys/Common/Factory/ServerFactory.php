<?php
namespace Pcs\Common\Factory;

use Pcs\Common\Config\Config;
use Pcs\Server\Http\HttpServer;

class ServerFactory
{
    public static function createHttpServer()
    {
        $serverConfig = Config::get('server');
        $swooleHttpServer = new \swoole_http_server($serverConfig['host'], $serverConfig['port']);
        $swooleHttpServer->set($serverConfig['swoole_config']);
        $httpServer = new HttpServer($swooleHttpServer);
        return $httpServer;
    }

    public static function createTcpServer()
    {

    }
}