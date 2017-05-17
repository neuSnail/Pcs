<?php
function gen()
{
    yield 1;
    echo 111;
    throw new Exception('123');
}

$gen = gen();
try {
    $ret = $gen->send(1);
} catch (Exception $e) {
    $gen->throw($e);
}