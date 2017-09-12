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
    
    public function schedule()
    {
        $coroutine = $this->task->getCoroutine();
        $value = $coroutine->current();

        $status = $this->handleSystemCall($value);
        if ($status !== self::SCHEDULE_CONTINUE) return $status;

        $status = $this->handleStackPush($value);
        if ($status !== self::SCHEDULE_CONTINUE) return $status;

        $status = $this->handleAsyncJob($value);
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

    private function handleAsyncJob($value)
    {
        if (!is_subclass_of($value, Async::class)) {
            return self::SCHEDULE_CONTINUE;
        }

        $value->execute([$this, 'asyncCallback']);

        return TaskStatus::TASK_WAIT;
    }

    public function asyncCallback($response, $exception = null)
    {
        if ($exception !== null
            && $exception instanceof \Exception
        ) {
            $this->throwException($exception, true);
        } else {
            $this->task->send($response);
            $this->task->run();
        }
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

    public function throwException($e, $isFirstCall = false)
    {
        if ($this->isStackEmpty()) {
            $this->task->getCoroutine()->throw($e);
            return;
        }

        try {
            if ($isFirstCall) {
                $coroutine = $this->task->getCoroutine();
            } else {
                $coroutine = $this->stack->pop();
            }

            $this->task->setCoroutine($coroutine);
            $coroutine->throw($e);

            $this->task->run();
        } catch (\Exception $e) {
            $this->throwException($e);
        }
    }
}