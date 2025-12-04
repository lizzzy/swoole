<?php
$server = new Swoole\Server("0.0.0.0", 8888);
$server->set([
    "open_eof_split" => true,
    "package_eof" => "eof",
]);
$server->on("Receive", function ($serv, $fd, $reactor_id, $data) {
    echo "Receive: " . $data . PHP_EOL;
});
$server->start();