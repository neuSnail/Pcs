<?php
namespace Pcs\Frame\Db\Mysqli;

use Pcs\Frame\DriverInterface;
use Pcs\Server\ServerHolder;

class Mysqli implements DriverInterface
{
    public $callback;
    private $sql;

    public function query($sql)
    {
        // TODO: Implement query() method.
        $this->sql=$sql;
        yield $this;
    }

    public function execute(callable $callback)
    {
        // TODO: Implement execute() method.
        $this->callback = $callback;
        $serv = ServerHolder::getServer();
        $serv->task($this->sql, -1, [$this, 'queryReady']);

    }

    public function queryReady(\swoole_http_server $serv, $task_id, $data)
    {
        $queryResult = unserialize($data);
        $exception = null;
        // var_dump($conn);
        if ($queryResult->errno != 0) {

            $exception = new \Exception($queryResult->error);
        }
        call_user_func_array($this->callback, [$queryResult, $exception]);

    }
}