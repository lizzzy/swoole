<?php
$server = new Swoole\Server("0.0.0.0", 8888, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);   // 4.x版本后只支持命名空间，不再支持下划线风格
// $server = new Swoole\Server("0.0.0.0", 8888, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
// var_dump($server);
$setting = [
    'worker_num' => 6,
    'reactor_num' => 6,
    'task_worker_num' => 3,  // 设置task进程数，必须设置task回调
    'max_request' => 5, // worker进程在处理完5次请求后结束运行，重新创建一个worker进程，可防止worker内存溢出
    // 'max_conn' => 10    // 最大连接数，最小值(serv->worker_num+Swloole.task_worker_num)*2 + 32  (6+3)*2+32=50
    // 小于50警告 [2025-12-01 15:00:38 %69915.0]  WARNING Server::create(): max_connection must be bigger than 54, it's reset to 1048576
    // 'daemonize' => true,
];
$server->set($setting);

$a = 'hello';
// function test_start($serv) {
//     echo "worker_pid: " . $serv->worker_pid . PHP_EOL;
// }
// $server->on("WorkerStart", 'test_start');

/* class Test {
    function  test_start($serv) {
        echo "worker_pid: " . $serv->worker_pid . PHP_EOL;
    }
}
$test = new Test();
$server->on("WorkerStart", [$test, "test_start"]); */

/* class Test {
    public static function  test_start($serv) {
        echo "worker_pid: " . $serv->worker_pid . PHP_EOL;
    }
}
$test = new Test();
$server->on("WorkerStart", "Test::test_start"); */
// $server->on("WorkerStart", function($serv) use($a) {
    // echo "worker_pid:". $serv->worker_pid . PHP_EOL; 循环打印所有9个Worker pid
    /* if($serv->taskworker) {
        echo "task_worker_pid:". $serv->worker_pid . PHP_EOL;
    } else {
        echo "worker_pid:". $serv->worker_pid . PHP_EOL;
    } */
//     echo $a;
// });
$server->on("Start", function($serv){
    // echo "master_pid:". $serv->master_pid . PHP_EOL;
    // echo "manager_pid:". $serv->manager_pid . PHP_EOL; 
    swoole_set_process_name("php:master");
});
$server->on("WorkerStart", function($serv, $worker_id){
    /* echo "worker_pid:". $serv->worker_pid . PHP_EOL;
    echo "worker_id:". $serv->worker_id . PHP_EOL; */

    if($serv->taskworker) {
        swoole_set_process_name("php:task");
    } else {
        swoole_set_process_name("php:worker");
    }
});
$server->on("ManagerStart", function($serv){
    swoole_set_process_name("php:manager");
});

$server->on("WorkerError", function($serv, $worker_id, $worker_pid, $exit_code, $signal) {
    echo "worker_id: " . $worker_id . ", worker_pid: " . $worker_pid . ", exit_code: " . $exit_code . ", signal: " . $signal . PHP_EOL;
});
$server->on("WorkerStop", function($serv, $worker_id) {
    echo "workerstop_id: " . $worker_id . PHP_EOL;
});
$server->on("connect", function($serv, $fd, $reactor_id) {
    echo "fd: " . $fd . ", reactor_id: " . $reactor_id . PHP_EOL;
});

$server->on("Receive", function($serv){
    // var_dump($serv->setting);
    /* 
        array(6) {
            ["worker_num"]=>
            int(6)
            ["reactor_num"]=>
            int(6)
            ["task_worker_num"]=>
            int(3)
            ["max_request"]=>
            int(5)
            ["output_buffer_size"]=>
            int(4294967295)
            ["max_connection"]=>
            int(100000)
        }
    */
    /* echo "master_pid:". $serv->master_pid . PHP_EOL;
    echo "manager_pid:". $serv->manager_pid . PHP_EOL; 
    echo "worker_pid:". $serv->worker_pid . PHP_EOL; 
    echo "worker_id:". $serv->worker_id . PHP_EOL; */

    /* foreach ($serv->connections as $fd) {
        echo "fd: " . $fd . PHP_EOL;
    } */
});
$server->on("Task", function(){});
// $server->on("Packet", function(){});

$server->start();
