<?php
namespace neuSnail\Server;

class Scheduler
{
    protected $maxTaskId;
    protected $taskMap;
    protected $taskQueue;

    public function __construct()
    {
        $this->taskQueue = new \SplQueue();
    }

    public function newTask(\Generator $coroutine)
    {
        $taskId = ++$this->maxTaskId;
        $task = new Task($taskId, $coroutine);
        $this->taskMap[$taskId] = $task;
        $this->enqueue($task);
        return $taskId;
    }

    public function enqueue(Task $task)
    {
        $this->taskQueue->enqueue($task);
    }

    public function run()
    {
        while (!$this->taskQueue->isEmpty()) {
            $task = $this->taskQueue->dequeue();
            $returnVal = $task->run();
            if ($returnVal instanceof SystemCall) {
                $returnVal($task, $this);
                continue;
            }

            if ($task->isFinished()) {
                unset($this->taskMap[$task->getTaskId()]);
            } else {
                $this->enqueue($task);
            }
        }
    }

    public function killTask($taskId)
    {
        if (!isset($this->taskMap[$taskId])) {
            return false;
        }
        unset($this->taskMap[$taskId]);

        foreach ($this->taskQueue as $i => $task) {
            if ($task->getTaskId() === $taskId) {
                unset($this->taskQueue[$i]);
                break;
            }
        }

        return true;
    }

    public function createTask(){

    }
}