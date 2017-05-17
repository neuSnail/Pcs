<?php
include '../vendor/autoload.php';
include '../script/boot.php';
$runmode = new \Pcs\Common\RunMode\RunMode();
$mode = $runmode->getRunMode();
$configLoader = \Pcs\Common\Config\ConfigLoader::getInstance();
$configLoader->load();

/*
$file = new \Pcs\Common\File\File();
$ret = $file->isType('ph', 'a.php');
var_dump($ret);*/
\Pcs\Common\Config\Config::init();
$ret=\Pcs\Common\Config\Config::get('test.array.1');
var_dump($ret);