<?php
include 'Task.php';
class Scheduler
{
    protected $maxTaskId;
    protected $taskMap;
    protected $taskQueue;

    public function __construct()
    {
        $this->taskQueue = new SplQueue();
    }

    public function newTask(Generator $coroutine)
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
                continue;
            }
        }
    }
}