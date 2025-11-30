<?php

//创建WebSocket Server对象，监听0.0.0.0:9502端口。
$ws = new Swoole\WebSocket\Server('0.0.0.0', 9502);

// 监听WebSocket连接打开事件
$ws->on('Open', function ($ws, $request) {
    while (true) {
        $time = date("Y-m-d H:i:s");
        echo "[ {$time} ], Message: {$request->fd} is in!\n";
        $ws->push($request->fd, "hello, welcome!{$time}\n");    // $request->fd 句柄，标识客户端的一个标志，每10秒发送一条信息，模拟后台通知信息
        Swoole\Coroutine::sleep(10);    // php原生函数会直接赞同整个进程的执行，事件内部不同协程来处理请求，使用Swoole提供休眠函数，针对当前协程休眠
    }
});

// 监听WebSocket消息事件
$ws->on('Message', function ($ws, $frame) {
    $time = date("Y-m-d H:i:s");
    echo "Message: {$frame->data}\n";   // data获取前端发送信息
    $ws->push($frame->fd, "[ {$time} ] Server: {$frame->data}");    // 向fd唯一标识符push信息，第二个参数发挥前端相同信息
});

// 监听WebSocket连接关闭事件
$ws->on('Close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();