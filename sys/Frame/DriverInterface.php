<?php
namespace Pcs\Frame;

use Pcs\Coroutine\Async;

interface DriverInterface extends Async
{

    public function query($sql);

    public function queryReady(\swoole_http_server $serv, $task_id, $data);
}