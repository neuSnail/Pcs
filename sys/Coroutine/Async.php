<?php
namespace Pcs\Coroutine;
interface Async
{
    public function execute(callable $callback);
}