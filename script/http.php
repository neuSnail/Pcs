<?php
require 'boot.php';
$server = \Pcs\Common\Factory\ServerFactory::createHttpServer();
$application = new \Pcs\Common\Application\Application();
$application->startHttpServer($server);