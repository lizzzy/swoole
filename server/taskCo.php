<?php
$server = new Swoole\Server("0.0.0.0", 8888);
$server->set([
    "worker_num" => 2,
    "task_worker_num" => 2,
]);
$server->on("Receive", function ($serv, $fd, $reactor_id, $data) {
    echo "Worker {$serv->worker_id} 处理 fd = {$fd} \n";
    $res = $serv->taskCo(['a'], 3);
    echo "Worker {$serv->worker_id} 完成 fd = {$fd} \n";
});

$server->on("Task", function ($serv, $task_id, $src_workd,  $data) {
    echo "task " . $data . PHP_EOL;
    // sleep(5);
    return "success" . $data;
});
$server->on("Finish", function ($serv, $task_id, $data) {
    echo "finish: " . $data . PHP_EOL;
});
$server->start();
/* 
    ontask不设置sleep - 单Worker处理
    root@ubuntu:/var/www/html/dist/server# php taskCo.php 
    Worker 0 处理 fd = 1 
    task a
    Worker 0 完成 fd = 1 
    Worker 0 处理 fd = 2 
    task a
    Worker 0 完成 fd = 2 

    Worker 0 处理 fd=1  → taskCo投递 → 立即返回 → Worker 0继续
    Worker 0 处理 fd=2  → taskCo投递 → 立即返回 → Worker 0继续
    Task太快,Worker 0来得及处理,不需要其他Worker


    ontask设置sleep- 多Worker处理
    root@ubuntu:/var/www/html/dist/server# php taskCo.php 
    Worker 1 处理 fd = 1 
    task a
    Worker 0 处理 fd = 2 
    task a
    Worker 1 完成 fd = 1 
    [2025-12-04 07:32:06 *122769.1] WARNING php_swoole_server_onFinish() (ERRNO 2003): task[0] has expired
    Worker 0 完成 fd = 2 
    [2025-12-04 07:32:09 *122768.0] WARNING php_swoole_server_onFinish() (ERRNO 2003): task[0] has expired
    
    时间线:

    0秒: 请求1到达 → 分配Worker 0
        Worker 0 taskCo投递 → 协程挂起(等Task 5秒)
        Worker 0状态: 挂起中

    1秒: 请求2到达 → Worker 0还在挂起
        → Swoole自动分配给空闲的Worker 1
        Worker 1 taskCo投递 → 协程挂起

    5秒: Task完成
        Worker 0协程恢复
        Worker 1协程恢复

    协程挂起时Worker可处理其他请求,Task耗时长时触发多Worker并发。
*/