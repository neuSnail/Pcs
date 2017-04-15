<?php
/**
 * Created by IntelliJ IDEA.
 * User: winglechen
 * Date: 15/11/9
 * Time: 13:37
 */
include 'Zanscheduler.php';
include 'taskId.php';

class Task
{
    protected $taskId = 0;
    protected $parentId = 0;
    protected $coroutine = null;
    protected $context = null;

    protected $sendValue = null;
    protected $scheduler = null;
    protected $status = 0;

    public static function execute($coroutine, $context = null, $taskId = 0, $parentId = 0)
    {
        if ($coroutine instanceof \Generator) {
            $task = new Task($coroutine, $context, $taskId, $parentId);
            $task->run();

            return $task;
        }

        return $coroutine;
    }

    public function __construct(\Generator $coroutine, $context = null, $taskId = 0, $parentId = 0)
    {
        $this->coroutine = $coroutine;
        $this->taskId = $taskId ? $taskId : TaskId::create();
        $this->parentId = $parentId;

        /*if ($context) {
            $this->context = $context;
        } else {
            $this->context = new Context();
        }*/

        $this->scheduler = new Scheduler($this);
    }

    public function run()
    {
        while (true) {
            try {
                if ($this->status === Signal::TASK_KILLED) {
                    $this->fireTaskDoneEvent();
                    break;
                }

                $this->status = $this->scheduler->schedule();
                switch ($this->status) {
                    case Signal::TASK_KILLED:
                        return null;
                    case Signal::TASK_SLEEP:
                        return null;
                    case Signal::TASK_WAIT:
                        return null;
                    case Signal::TASK_DONE;
                        $this->fireTaskDoneEvent();
                        return null;
                }
            } catch (\Exception $e) {
                $this->scheduler->throwException($e);
            }
        }
    }

    public function sendException($e)
    {
        if ($this->scheduler->isStackEmpty()) {
            $this->coroutine->throw($e);
        }

        $this->scheduler->throwException($e);
    }

    public function send($value)
    {
        $this->sendValue = $value;
        return $this->coroutine->send($value);
    }

    public function getTaskId()
    {
        return $this->taskId;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function getSendValue()
    {
        return $this->sendValue;
    }

    public function getResult()
    {
        return $this->sendValue;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($signal)
    {
        $this->status = $signal;
    }

    public function getCoroutine()
    {
        return $this->coroutine;
    }

    public function setCoroutine(\Generator $coroutine)
    {
        $this->coroutine = $coroutine;
    }

    public function fireTaskDoneEvent()
    {
        $evtName = 'task_event_' . $this->taskId;
    }
}

function gen()
{
    $gen1Ret = (yield gen1());
    echo 'gen1Ret=' . $gen1Ret."\n";
    yield 'genDone';
}

function gen1()
{
    $gen2Ret = (yield gen2());
    echo 'dump in gen1 : gen2Ret=' . $gen2Ret . "\n";
    $gen3Ret = (yield gen3());
    echo 'dump in gen1 : gen3Ret=' . $gen3Ret . "\n";
    yield $gen3Ret;
}

function gen2()
{
   // yield 'gen2Result1';
    yield 'gen2Result2';
}

function gen3()
{
    yield 'gen3Ret';
}

$task = new Task(gen());
$ret = $task->run();
var_dump($ret);
//var_dump($task->getStatus());