<?php
return [
    'host' => '127.0.0.1',
    'port' => '9501',

    'swoole_config' => [
        'worker_num' => 50,
        'task_worker_num'=>100
    ],

    'time_out' => 10000//micro sec
];