<?php
$server = new Swoole\Server("0.0.0.0", 8888);
$server->set([
    "worker_num" => 2,
    "task_worker_num" => 2,
]);

$server->on("Receive", function ($serv, $fd, $reactor_id, $data) {
    /* $serv->task("task test", -1, function() {   // 第三个参数function，相当于onFinish，同时onFinish不再回调
        echo "task finish" . PHP_EOL;
    }); */
    // $serv->task()   // 异步执行，不等待  流程: Receive → Task → Finish ✅
    // $serv->taskwait()   // 同步等待，阻塞    流程: Receive → Task → 直接返回 ❌ 不触发Finish
    // echo "1. Receive开始" . PHP_EOL;

    // $res = $serv->taskwait("task wait data", 3);   // 阻塞运行，直到返回结果才往下执行

    $res = $serv->taskWaitMulti(['a', 'b', 'c'], 5);    // 阻塞5秒，这5秒内，Worker进程完全阻塞，无法处理其他请求
    /* 
        Worker进程:
        ├─ 请求1进来
        ├─ taskWaitMulti投递
        ├─ 阻塞等待5秒 ⏸️ (卡死,无法处理其他请求)
        ├─ 收到结果
        └─ 继续执行

        task a
        task b
        task c
        array(2) {               // 只返回成功的
            [0] => "successa"
            [1] => "successb"
            // 超时的直接丢弃
        }
        login
        WARNING: task[2] has expired  
    */
    // $res = $serv->taskCo(['a', 'b', 'c'], 5);    // 协程挂起，这5秒内容，Worker进程可以处理其他请求
    /* 
        Worker进程:
        ├─ 请求1进来
        ├─ taskCo投递
        ├─ 协程挂起 🔄 (让出CPU)
        ├─ 处理请求2 ✅
        ├─ 处理请求3 ✅
        ├─ 请求1的Task完成,协程恢复
        └─ 继续执行请求1

        task a
        task b
        task c
        array(3) {
        [0]=>
        string(8) "successa"
        [1]=>
        string(8) "successb"
        [2]=>
        bool(false)     // 保持数组结构，保留超时位置，超时用false占位
        }
        login
        [2025-12-04 07:09:24 *121151.0] WARNING php_swoole_server_onFinish() (ERRNO 2003): task[2] has expired
    */
    var_dump($res);
    echo "login" . PHP_EOL;
});
$server->on("Task", function ($serv, $task_id, $src_workd,  $data) use ($server) {
    echo "task " . $data . PHP_EOL;
    sleep(3);
    return "success" . $data;
    // return 与下面写法作用相同
    // $server->finish("success");
});
$server->on("Finish", function ($serv, $task_id, $data) {
    echo "finish: " . $data . PHP_EOL;
});
$server->start();