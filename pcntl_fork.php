<?php
// $pid = pcntl_fork();    // fork是创建一个子进程，父进程和子进程都从fork位置开始向下继续执行
// 父进程在执行过程中，得到fork返回值为子进程号
// 子进程执行过程中，得到的值为0
// echo $pid.PHP_EOL;
/* 
php pcntl_fork.php 
54216   // 父进程执行结果：子进程号
0       // 子进程执行结果：0
*/

/* if ($pid > 0) {
    echo "父进程".PHP_EOL;
} else if($pid == 0) {
    echo "子进程".PHP_EOL;
} */

// 模拟多进程，先打印111,10秒后打印222
/* if ($pid = pcntl_fork() == 0) {
    sleep(10);
    echo "222".PHP_EOL;
}
if($pid == 0) {
    if(pcntl_fork() == 0) {
        echo "111".PHP_EOL;
    }
} */

// 进程间通信
$str = 'abc';
$pid = pcntl_fork();
if( $pid > 0) {
    $str .= '111';  
    echo $str.PHP_EOL;  // 父进程 abc111
} else {
    echo $str.PHP_EOL;  // 子进程 abc
}
// 内存不共享，父进程与子进程变量不同