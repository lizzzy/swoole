<?php
$client = new Swoole\Client(SWOOLE_SOCK_TCP);
if(!$client->connect("127.0.0.1", 9503, -1)) {
    exit("connect failed. Error: ".$client->errCode."".$client->errMsg. "\n");
};

var_dump($client->isConnected());   // bool(true)
// var_dump($client->getSock());   // getSock())返回一个socket扩展句柄资源，系统环境中安装socket扩展使用
var_dump($client->getsockname()); 
/* 
    array(2) {
        ["port"]=>
        int(51756)
        ["host"]=>
        string(9) "127.0.0.1"
    }
*/

$client->send("Hello World\n");
echo $client->recv();