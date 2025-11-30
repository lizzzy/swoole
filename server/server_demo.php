<?php
// $server = new Swoole\Server("0.0.0.0",8888, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);   // 4.x版本后只支持命名空间，不再支持下划线风格
$server = new Swoole\Server("0.0.0.0",8888, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
// var_dump($server);

// $server->on("Receive", function(){});
$server->on("Packet", function(){});

$server->start();
