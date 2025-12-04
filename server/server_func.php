<?php
$server = new Swoole\Server("0.0.0.0", 8888);

$server->set([
    "worker_num" => 3,
    // "task_worker_num"=> 2,
]);
$server->on("WorkerStart", function ($serv, $worker_id) {
    echo "worker_id: ". $serv->worker_id . PHP_EOL;
});
$server->on("PipeMessage", function ($serv, $src_worker_id, $message) {
    // echo "--- PipeMessage ---\n";
    // echo "当前Worker: {$serv->worker_id}\n";
    // echo "来自Worker: {$src_worker_id}\n";
    // echo "消息内容: {$message}\n\n";
});



$server->on("Connect", function ($serv, $fd, $reactor_id) {
    // var_dump($serv->getClientInfo($fd));
    // var_dump($serv->getClientList());
    /* 
        root@ubuntu:/var/www/html/dist/server# php server_func.php 
        worker_id: 0
        worker_id: 1
        worker_id: 2
        array(1) {  # 第一个telnet连接
        [0]=>
        int(1)
        }
        array(1) {  # 第二个telnet连接
        [0]=>
        int(2)
        }

    */
    /* foreach($serv->getClientList() as $fd) {
        $serv->send($fd,"connect success" . PHP_EOL);
    } */

    /* $start_fd = 0;
    while (true) {
        $conn_list = $serv->getClientList($start_fd, 10);
        if($conn_list=== false or count($conn_list) == 0) {
            echo "finish\n";
            break;
        }
        $start_fd = end($conn_list);
        var_dump($conn_list);
        foreach($conn_list as $fd) {
            $serv->send($fd, "broadcast");
        }
    } */
    /* $uid = mt_rand(888, 999); // 可以是用户、设备
    $serv->bind($fd, $uid);
    var_dump($serv->getClientInfo($fd)); */
});
$server->on("Task", function ($serv) {});
$server->on("Receive", function ($serv, $fd, $reactor_id, $data) {
    // echo "当前Worker: {$serv->worker_id} 收到请求\n";
    
    // 发送给指定Worker
    // $serv->sendMessage("消息", 0);
    /* 
        root@ubuntu:/var/www/html/dist/server# php server_func.php 
        worker_id: 3
        worker_id: 4
        worker_id: 0
        worker_id: 1
        worker_id: 2
        当前Worker: 2 收到请求
        --- PipeMessage ---
        当前Worker: 0
        来自Worker: 2
        消息内容: 消息
    */

    // 发送给所有Worker
    /* $worker_num = $serv->setting['worker_num'];
    for ($i = 0; $i < $worker_num; $i++) {
        if ($i !== $serv->worker_id) {  // 不发给自己
            $serv->sendMessage("广播消息", $i);
        }
    } */
    /* 
        root@ubuntu:/var/www/html/dist/server# php server_func.php 
        worker_id: 3
        worker_id: 4
        worker_id: 0
        worker_id: 1
        worker_id: 2
        当前Worker: 2 收到请求
        --- PipeMessage ---
        当前Worker: 0
        来自Worker: 2
        消息内容: 广播消息

        --- PipeMessage ---
        当前Worker: 1
        来自Worker: 2
        消息内容: 广播消息

        当前Worker: 2 收到请求
        --- PipeMessage ---
        当前Worker: 1
        来自Worker: 2
        消息内容: 广播消息

        --- PipeMessage ---
        当前Worker: 0
        来自Worker: 2
        消息内容: 广播消息
    */

    // 发送给TaskWorker
    // $task_id = $serv->setting["worker_num"];
    // $serv->sendMessage("task消息", $task_id);
    /* 
        worker_id: 3
        worker_id: 4
        worker_id: 0
        worker_id: 1
        worker_id: 2
        当前Worker: 2 收到请求
        --- PipeMessage ---
        当前Worker: 3
        来自Worker: 2
        消息内容: task消息
    */


    // 方法1: 轮询发送给下一个Worker
    // $target_worker = ($serv->worker_id + 1) % $serv->setting['worker_num'];  // 0→1, 1→2, 2→0
    // $serv->sendMessage("hello from {$serv->worker_id}， 接收数据：" . trim($data), $target_worker);
    /* 
        root@ubuntu:/var/www/html/dist/server# php server_func.php 
        worker_id: 3
        worker_id: 4
        worker_id: 0
        worker_id: 1
        worker_id: 2
        当前Worker: 2 收到请求
        --- PipeMessage ---
        当前Worker: 0
        来自Worker: 2
        消息内容: hello from 2， 接收数据：haha

        当前Worker: 2 收到请求
        --- PipeMessage ---
        当前Worker: 0
        来自Worker: 2
        消息内容: hello from 2， 接收数据：hehe
    */
    
    // $serv->send($fd, "OK\n");

    if(trim($data) == "stop") {
        // $serv->stop($serv->worker_id);
        
    } else if(trim($data) == "close") {
        $serv->close($fd);
    } else {
        echo "data: " . $data . PHP_EOL;
    }
});
$server->on("WorkerStop", function ($serv, $worker_id) {
    echo "worker_stop_id: " . $worker_id . PHP_EOL;
});
$server->on("Close", function ($serv, $fd) {
    echo "close_fd: " . $fd . PHP_EOL;
});
$server->start();