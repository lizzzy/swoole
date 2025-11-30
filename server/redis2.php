<?php
require __DIR__ . '/../vendor/autoload.php'; 
use Predis\Client;

// 启动3个并发协程
for($i = 0; $i < 3; $i++) {
    go(function() use ($i) {
        $redis = new Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
        
        echo "协程 $i 开始\n";
        $redis->set("key$i", "value$i");  // IO操作时自动切换
        echo "协程 $i 完成\n";
    });
}