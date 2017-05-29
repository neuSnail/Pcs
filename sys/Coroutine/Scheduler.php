<?php
namespace Pcs\Coroutine;

class Scheduler
{
    private $task;
    private $stack;
    const SCHEDULE_CONTINUE = 10;

    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->stack = new \SplStack();
    }

    /*
     * 调度器的调度优先级是先检查返回值是不是系统调用,
     * 然后查看返回值是不是协程子调用,是的话母协程入栈,子协程交给task进行下一轮调用。
     */
    public function schedule()
    {
        $coroutine = $this->task->getCoroutine();
        $value = $coroutine->current();

        $status = $this->handleSystemCall($value);
        if ($status !== self::SCHEDULE_CONTINUE) return $status;

        $status = $this->handleStackPush($value);
        if ($status !== self::SCHEDULE_CONTINUE) return $status;

        $status = $this->handelYieldValue($value);
        if ($status !== self::SCHEDULE_CONTINUE) return $status;

        $status = $this->handelStackPop();
        if ($status !== self::SCHEDULE_CONTINUE) return $status;


        return TaskStatus::TASK_DONE;
    }

    public function isStackEmpty()
    {
        return $this->stack->isEmpty();
    }

    private function handleSystemCall($value)
    {
        if (!$value instanceof SystemCall) {
            return self::SCHEDULE_CONTINUE;
        }
    }

    private function handleStackPush($value)
    {
        if (!$value instanceof \Generator) {
            return self::SCHEDULE_CONTINUE;
        }

        $coroutine = $this->task->getCoroutine();
        $this->stack->push($coroutine);
        $this->task->setCoroutine($value);

        return TaskStatus::TASK_CONTINUE;
    }

    private function handelYieldValue($value)
    {
        if (!$this->task->valid()) {
            return self::SCHEDULE_CONTINUE;
        }

        $ret = $this->task->send($value);
        return TaskStatus::TASK_CONTINUE;
    }


    private function handelStackPop()
    {
        if ($this->isStackEmpty()) {
            return self::SCHEDULE_CONTINUE;
        }

        $coroutine = $this->stack->pop();
        $this->task->setCoroutine($coroutine);

        $value = $this->task->getSendVal();
        $this->task->send($value);

        return TaskStatus::TASK_CONTINUE;
    }
}