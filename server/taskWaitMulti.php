<?php
$server = new Swoole\Server("0.0.0.0", 8888);
$server->set([
    "worker_num" => 2,
    "task_worker_num" => 2,
]);
$server->on("Receive", function ($serv, $fd, $reactor_id, $data) {
    echo "Worker {$serv->worker_id} 处理 fd = {$fd} \n";
    $res = $serv->taskWaitMulti(['a'], 3);
    echo "Worker {$serv->worker_id} 完成 fd = {$fd} \n";
});

$server->on("Task", function ($serv, $task_id, $src_workd,  $data) {
    echo "task " . $data . PHP_EOL;
    // sleep(3);
    return "success" . $data;
});
$server->on("Finish", function ($serv, $task_id, $data) {
    echo "finish: " . $data . PHP_EOL;
});
$server->start();
/* 
    root@ubuntu:/var/www/html/dist/server# php taskCo.php 
    Worker 0 处理 fd = 1 
    task a
    Worker 0 完成 fd = 1 
    Worker 0 处理 fd = 2 
    task a
    Worker 0 完成 fd = 2 

    Worker 0 阻塞期间完全卡死，请求2必须等Worker 0 处理完才能进来
    串行处理，无并发
*/