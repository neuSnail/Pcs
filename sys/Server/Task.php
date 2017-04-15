<?php
namespace neuSnail\Server;

class Task
{
    protected $taskId;
    protected $coroutine;
    protected $sendValue = null;
    protected $beforeFirstYield = true;

    public function __construct($taskId, \Generator $coroutine)
    {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }

    public function getTaskId()
    {
        return $this->taskId;
    }

    public function setSendValue($sendValue)
    {
        $this->sendValue = $sendValue;
    }

    public function run()
    {
        if ($this->beforeFirstYield == true) {
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } else {
            $returnVal = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $returnVal;
        }
    }

    public function isFinished()
    {
        return !$this->coroutine->valid();
    }
}