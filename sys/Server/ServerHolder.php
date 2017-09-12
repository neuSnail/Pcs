<?php
namespace Pcs\Server;

class ServerHolder
{
    private static $server;

    public static function setServer(\swoole_http_server $server)
    {
        self::$server = $server;
    }

    public static function getServer()
    {
        return self::$server;
    }
}