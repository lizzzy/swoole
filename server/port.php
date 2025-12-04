<?php
$server = new Swoole\Server("0.0.0.0", 8888);
$port = $server->listen("0.0.0.0", 9999, SWOOLE_SOCK_TCP);
$port->set([
    "open_websocket_protoctl" => true,// 设置使这个端口支持WebSocket协议
    "open_http_protoctl" => true,// 设置使这个端口关闭http协议
]);
$port->on("Receive", function($serv, $fd, $reactor_id, $data) {
    // echo "9999: " . $data . PHP_EOL;
});
$server->on("connect", function ($serv, $fd, $reactor_id) {
    var_dump($serv->getClientInfo($fd)['server_port']); // 不同端口分别处理
    // var_dump($serv->stats());
});
$server->on("Receive", function ($serv, $fd, $reactor_id, $data) {
    echo "8888: " . $data . PHP_EOL;
});
$server->start();