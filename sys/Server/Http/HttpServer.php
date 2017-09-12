<?php
namespace Pcs\Server\Http;

use Pcs\Frame\Db\Mysqli\QueryResult;
use Pcs\Server\Server;
use Pcs\Network\Request\RequestHandler;
use Pcs\Common\Config\Config;
use Pcs\Server\ServerHolder;


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
        $this->swooleHttpServer->on('task', [$this, 'onTask']);
        $this->swooleHttpServer->on('finish', [$this, 'onFinish']);


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
        ServerHolder::setServer($this->swooleHttpServer);
    }

    public function onWorkerStop(\swoole_http_server $server, $workerId)
    {
        echo " worker :$workerId stop ..... \n";
    }

    public function onWorkerError(\swoole_http_server $server, $workerId, $workerPid, $exitCode)
    {
        echo "worker: $workerId error code:$exitCode \n";
    }

    public function onRequest(\swoole_http_request $request, \swoole_http_response $response)
    {

        $requestHandler = new RequestHandler($request, $response);
        $requestHandler->handle();
        /* $this->swooleHttpServer->task('select * from user WHERE id=1',
             -1,
             function (\swoole_http_server $serv, $task_id, $data) {
                 $data = unserialize($data);
                 var_dump($data);
             });*/

    }

    public function onTask(\swoole_http_server $serv, $task_id, $from_id, $sql)
    {
        static $conn = null;
        $config = Config::get('mysql');
        if ($conn == null) {
            $conn = new \mysqli($config['host'], $config['username'],
                $config['password'], $config['database'], $config['port']);
            if (!$conn) {
                $conn = null;
                throw new \Exception('connect to db failed');
            }
        }
        $query = $conn->query($sql);
        if (!$query){
            throw new \Exception($conn->error);
        }
        $ret=$query->fetch_assoc();
        $queryResult = new QueryResult($conn, $ret);
        $serv->finish(serialize($queryResult));

    }

    public function onFinish($serv, $task_id, $data)
    {
        echo "AsyncTask[$task_id] Finish: $data" . PHP_EOL;
    }
}