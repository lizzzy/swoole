<?php
$server = new Swoole\Server("0.0.0.0",9504, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);   // SWOOLE_PROCESS类型模式，默认SWOOLE_SOCK_TCP
// 监听数据接收事件
$server->on("Packet", function ($server, $data, $clientInfo){
    var_dump($clientInfo);
    $server->sendto($clientInfo["address"], $clientInfo["port"], "Server UDP: ".$data);
});

$server->start();