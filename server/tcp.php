<?php
$server = new Swoole\Server("0.0.0.0", 9503);

// 监听连接进入事件
$server->on("Connect", function ($server, $fd) {
    echo "Client: Connect.\n";
});
// 监听数据接收时间
$server->on("Receive", function ($server, $fd, $reactor_id, $data) {
    $server->send($fd, "Server TCP:". $data);
});
// 监听连接关闭事件
$server->on("Close", function ($server, $fd) {
    echo "Client: Close.\n";
});

$server->start();