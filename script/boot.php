<?php
use Pcs\Common\BootStrap\BootStrap;

require '../vendor/autoload.php';
$rootPath = realpath(__DIR__ . '/../');
$boot = new BootStrap();
$boot->boot($rootPath);