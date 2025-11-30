<?php
use Swoole\Server;
use Swoole\Timer;

define("DB_FILE", __DIR__ . "/db");

$server = new Server("0.0.0.0", 9501, SWOOLE_BASE);

// 加载数据
if(is_file(DB_FILE)) {
    $server->data = unserialize(file_get_contents(DB_FILE));
} else {
    $server->data = [];
}

$server->on('Receive', function ($server, $fd, $reactor_id, $data) {
    $data = trim($data);
    $parts = explode("\r\n", $data);
    
    // 解析Redis协议
    $cmd = [];
    for ($i = 0; $i < count($parts); $i++) {
        if (isset($parts[$i][0]) && $parts[$i][0] == '$' && isset($parts[$i+1])) {
            $cmd[] = $parts[$i+1];
            $i++;
        }
    }
    
    if (empty($cmd)) {
        return;
    }
    
    $command = strtoupper($cmd[0]);
    
    if ($command == 'SET' && count($cmd) >= 3) {
        $server->data[$cmd[1]] = ['type' => 'string', 'value' => $cmd[2]];
        $server->send($fd, "+OK\r\n");
        
    } elseif ($command == 'GET' && count($cmd) >= 2) {
        $key = $cmd[1];
        if (isset($server->data[$key]) && $server->data[$key]['type'] == 'string') {
            $value = $server->data[$key]['value'];
            $server->send($fd, "$" . strlen($value) . "\r\n" . $value . "\r\n");
        } else {
            $server->send($fd, "$-1\r\n");
        }
        
    } elseif ($command == 'SADD' && count($cmd) >= 3) {
        $key = $cmd[1];
        if (!isset($server->data[$key])) {
            $server->data[$key] = ['type' => 'set', 'value' => []];
        }
        $count = 0;
        for ($i = 2; $i < count($cmd); $i++) {
            if (!in_array($cmd[$i], $server->data[$key]['value'])) {
                $server->data[$key]['value'][] = $cmd[$i];
                $count++;
            }
        }
        $server->send($fd, ":" . $count . "\r\n");
        
    } elseif ($command == 'SMEMBERS' && count($cmd) >= 2) {
        $key = $cmd[1];
        if (isset($server->data[$key]) && $server->data[$key]['type'] == 'set') {
            $members = $server->data[$key]['value'];
            $response = "*" . count($members) . "\r\n";
            foreach ($members as $member) {
                $response .= "$" . strlen($member) . "\r\n" . $member . "\r\n";
            }
            $server->send($fd, $response);
        } else {
            $server->send($fd, "*0\r\n");
        }
        
    } elseif ($command == 'KEYS' && count($cmd) >= 2) {
        $pattern = $cmd[1];
        $keys = [];
        
        if ($pattern == '*') {
            $keys = array_keys($server->data);
        } else {
            $regex = '/' . str_replace(['*', '?'], ['.*', '.'], $pattern) . '/';
            foreach (array_keys($server->data) as $key) {
                if (preg_match($regex, $key)) {
                    $keys[] = $key;
                }
            }
        }
        
        $response = "*" . count($keys) . "\r\n";
        foreach ($keys as $key) {
            $response .= "$" . strlen($key) . "\r\n" . $key . "\r\n";
        }
        $server->send($fd, $response);
        
    } else {
        $server->send($fd, "-ERR unknown command\r\n");
    }
});

$server->on('WorkerStart', function($serv) use ($server) {
    Timer::tick(10000, function() use ($server) {
        file_put_contents(DB_FILE, serialize($server->data));
    });
});

$server->start();