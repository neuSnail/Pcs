<?php
namespace Pcs\Coroutine;

class Scheduler
{
    private $task;
    private $taskStack;
    const CO_CONTINUE = 1;

    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->taskStack = new \SplStack();
    }

    public function schedule()
    {
        $status = TaskStatus::TASK_DONE;
        $coroutine = $this->task->getCoroutine();
        $value = $coroutine->current();
        do {
            $status = $this->handelYieldValue($value);
            if ($status !== null) return $status;

            $status = $this->handelTaskStack();
            if ($status !== null) return $status;

        } while (0);

        return $status;
    }

    public function isStackEmpty()
    {
        return $this->taskStack->isEmpty();
    }


    private function handelYieldValue($value)
    {
        if (!$this->task->valid()) {
            return null;
        }

        $ret = $this->task->send($value);
        return TaskStatus::TASK_CONTINUE;
    }

    private function handelTaskStack()
    {
        if ($this->isStackEmpty()) {
            return null;
        }

        $coroutine = $this->taskStack->pop();
        $this->task->setCoroutine($coroutine);

        $value = $this->task->getSendValue();
        $this->task->send($value);

        return TaskStatus::TASK_CONTINUE;
    }
}