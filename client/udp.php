<?php
$client = new Swoole\Client(SWOOLE_SOCK_UDP);
if(!$client->connect("127.0.0.1", 9504, -1)) {
    exit("connect failed. Error: ".$client->errCode."".$client->errMsg. "\n");
};
$client->sendto("127.0.0.1","9504", "Hello World!\n");
echo $client->recv();
var_dump($client->getpeername());
/* 
    array(2) {
        ["port"]=>
        int(9504)
        ["host"]=>
        string(9) "127.0.0.1"
    }
*/
$client->close();