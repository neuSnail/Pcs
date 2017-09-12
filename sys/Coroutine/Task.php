<?php
namespace Pcs\Coroutine;

use Pcs\Network\Context\Context;

class Task
{
    private $coroutine;
    private $context;
    private $status;
    private $scheduler;
    private $sendValue;

    public function __construct(\Generator $coroutine, Context $context)
    {
        $this->coroutine = $coroutine;
        $this->context = $context;
        $this->scheduler = new Scheduler($this);

    }

    public function run()
    {
        $i = 0;
        while (true) {
            try {
                $this->status = $this->scheduler->schedule();
                switch ($this->status) {
                    case TaskStatus::TASK_WAIT:
                        echo "task status: TASK_WAIT      schedule times: ".$i++."\n";
                        return null;

                    case TaskStatus::TASK_DONE:
                        echo "task status: TASK_DONE      schedule times: ".$i++."\n";
                        return null;

                    case TaskStatus::TASK_CONTINUE;
                        echo "task status: TASK_CONTINUE  schedule times: ".$i++."\n";
                        break;
                }

            } catch (\Exception $e) {
                $this->scheduler->throwException($e);
            }
        }
    }

    public function execute()
    {

    }

    public function setCoroutine($coroutine)
    {
        $this->coroutine = $coroutine;
    }

    public function getCoroutine()
    {
        return $this->coroutine;
    }

    public function valid()
    {
        if ($this->coroutine->valid()) {
            return true;
        } else {
            return false;
        }
    }

    public function send($value)
    {
        $this->sendValue = $value;
        $ret = $this->coroutine->send($value);
        return $ret;
    }

    public function getSendVal()
    {
        return $this->sendValue;
    }
}