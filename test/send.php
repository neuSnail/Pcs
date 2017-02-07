<?php
/**
 * Created by PhpStorm.
 * User: zhangqiushi
 * Date: 17/2/1
 * Time: 下午10:09
 */
function gen()
{
    $sendValue = (yield 'a');
    var_dump($sendValue);//first dump 'c'
    $sendValue = (yield 'b');
    var_dump($sendValue);//last dump 'd'
}

$gen = gen();
var_dump($gen->send('c'));//second dump 'b'
var_dump($gen->current());
$gen->send('d');