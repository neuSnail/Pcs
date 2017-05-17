<?php
namespace Pcs\Server;
interface Server
{
    public function start();

    public function stop();

    public function reload();
}