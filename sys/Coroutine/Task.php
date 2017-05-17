<?php
namespace Pcs\Coroutine;

use Pcs\Network\Context\Context;

class Task
{
    private $coroutine;
    private $context;
    private $status;
    private $scheduler;

    public function __construct(\Generator $coroutine, Context $context)
    {
        $this->coroutine = $coroutine;
        $this->context = $context;
        $this->scheduler = new Scheduler($this);

    }

    public function run()
    {
        while (true) {
            try {
                $this->status = $this->scheduler->schedule();
                switch ($this->status) {
                    case TaskStatus::TASK_WAIT:
                        return null;

                    case TaskStatus::TASK_DONE:
                        return null;
                }

            } catch (\Exception $e) {

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
        $ret = $this->coroutine->send($value);
        return $ret;
    }
}