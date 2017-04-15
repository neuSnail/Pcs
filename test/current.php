<?php
/**
 * Created by PhpStorm.
 * User: zhangqiushi
 * Date: 17/2/18
 * Time: 下午1:54
 */

function gen()
{
    for ($i = 1; ; $i++) {
        yield $i;
    }
}

$gen = gen();