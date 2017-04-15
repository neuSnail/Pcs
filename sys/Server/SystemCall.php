<?php
namespace neuSnail\Server;

class SystemCall
{
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function __invoke(Task $task, Scheduler $scheduler)
    {
        $callback = $this->callback;
        return $callback($task, $scheduler);
    }

    public static function getTaskId()
    {
        return new self(
            function ($task,$schedule){

            }
        );
    }
}