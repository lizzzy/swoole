## 编程语言概述

> 使PHP开发人员可以编写高性能高并发的TCP、UDP、Unix Socket、HTTP、WebSokcet等服务，让PHP不再局限于Web领域。Swoole4 协程的城市将PHP带入了前所未有的时期，为心梗的提升提供了独一无二的可能性。Swoole 可以广泛应用于互联网、移动通信、云九三、网络游戏、物联网IOT、车联网、智能家居等领域。使PHP+Swoole可以使企业IT研发团队的效率大大提升，更加专注于开发创新产品

TCP、UDP、Unix Socket、HTTP、WebSocket，普通PHP也能做到，通常在进行普通Web开发时，都会借助一个服务器应用，如Apache或Nginx配合fastcgi进行实现。在Swoole中，只需要运行起Swoole服务就可以实现这些服务的挂载，还可以在外面套上Nginx，方便管理应用地址（域名）

### 静态语言

Java这类语言可以归结为静态语言，有固定的变量类型，必须编译后才能运行，特点是一次加载会直接将代码加载到内存中。电脑上的应用程序，直接执行一个程序的.exe文件，就能运行起来。编辑器打开exe或Java的Jar文件，会看到二进制内容

优点：

- 静态语言将代码一次加载到内存，效率高
- 静态语言会一次性将很多初始对象，类模板文件加载，调用时不用重新加载实例化

缺点：

通常都需要编译成一个可执行的中间文件，如果有代码更新，则必须重启整个程序



### 动态语言

PHP，Python、JavaScript可以归类为动态语言，特点是变量不用指定类型，随便一个文件就可以直接运行。即使JS的npm编译，实际上也是对代码进行混淆和格式化，并没有完全编译一个类似于Jar包的中间代码执行文件

优点：

随时修改随时上线，线上业务不用中断

缺点：

每一次运行一个脚本，需要所有相关文件加载一次（如果没别的优化，如OPcache），所有相关文件都要从硬盘读取、加载内存、实例化这些步骤重走一遍，效率低

PHP会是创业公司首选，方便更新迭代速度快，对线上业务影响小。当公司发展到一定规模，则会因为效率性能问题容易被Java、Golang等语言替代，对热更新、规范化上线等相关操作，会让静态语言需要编译或重启服务这类问题成为边缘化的小问题。性能效率才是中大型公司更重要考虑

```bash
# 安装php
apt install php8.1-cli
# 安装php-fpm
apt install php-fpm
sudo systemctl status php8.1-fpm
# 查找php.ini
php -i | grep ini
# 查看nginx配置
nginx -t 
vi /etc/nginx/nginx.conf

server {
    listen 80;
    server_name localhost;
    root /var/www/html;
    index index.php index.html;

    # 添加 PHP 处理
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;  # 根据版本调整
    }
}

sudo nginx -t
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm 

# 安装 Swoole 扩展
# 1. 安装依赖
sudo apt update
sudo apt install php-dev gcc make autoconf libc-dev pkg-config

# 2. 下载并编译 Swoole
cd /tmp
wget https://github.com/swoole/swoole-src/archive/v5.1.1.tar.gz
tar zxf v5.1.1.tar.gz
cd swoole-src-5.1.1
phpize
./configure
make
sudo make install

# 3. 创建配置文件
echo "extension=swoole.so" | sudo tee /etc/php/8.1/mods-available/swoole.ini

# 4. 启用扩展
sudo ln -s /etc/php/8.1/mods-available/swoole.ini /etc/php/8.1/cli/conf.d/20-swoole.ini
sudo ln -s /etc/php/8.1/mods-available/swoole.ini /etc/php/8.1/fpm/conf.d/20-swoole.ini

# 5. 重启 PHP-FPM
sudo systemctl restart php8.1-fpm

# 6. 验证
php -m | grep swoole
php --ri swoole


# 安装 Redis 服务器
sudo apt update
sudo apt install redis-server -y

# 启动并设置开机自启
sudo systemctl start redis-server
sudo systemctl enable redis-server

# 安装 PHP Redis 扩展
sudo apt install php8.1-redis -y

# 重启 PHP-FPM (如果使用)
sudo systemctl restart php8.1-fpm

# 重启 Nginx
sudo systemctl restart nginx

# 验证安装
redis-cli ping
php -m | grep redis
```

VSCode远程编辑

```bash
1. 安装扩展
VSCode 安装 Remote - SSH 扩展
2. 连接虚拟机
1. Ctrl+Shift+P
2. 输入 "Remote-SSH: Connect to Host"
3. 输入：root@虚拟机IP
4. 输入密码
5. 打开文件夹：/var/www/html

# 如遇权限，虚拟机SSH允许root登陆
# 编辑 SSH 配置
sudo nano /etc/ssh/sshd_config

# 找到并修改以下行：
PermitRootLogin yes
PasswordAuthentication yes

# 保存：Ctrl+O，回车，Ctrl+X

# 重启 SSH 服务
sudo systemctl restart sshd


# 查看php
ps -ef | grep php
# 查看端口
lsof -i tcp
```

## Swoole

```php
// http.php
<?php
$http = new Swoole\Http\Server('0.0.0.0', 9501);	// 实例化一个Server对象，传入两个构造函数，监听的IP地址和端口号
$http->on('Request', function($request, $response) {	// on()函数是一个监听函数，用于监听指定的事件，这里监听Request事件，监听到的内容通过回调函数的参数返回$request，$response参数用于返回响应事件
    echo '接收了请求', PHP_EOL;
    $response->header('Content-type', 'text/html;charset=utf-8');
    $response->end('<h1>Hello Swoole .#'.rand(1000, 9999).'</h1>');	// 使用$response的end方法，将响应输出指定的内容，并结束当前请求
});
echo '服务启动', PHP_EOL;
$http->start();
// 启动 php http.php  程序被挂载
// 浏览器访问 http://192.168.3.200:9501
```

现在的`Swoole`其实就已经是类似静态语言的运行方式，已经将改程序挂载起来成为一个独立的进程，相当于编译成一个类似于`jar`或`exe`文件，并且直接运行

修改文件不会对当前进程中的程序产生影响

更新修改需要重启服务

`echo` 打印在命令行，`Swoole`与传统开发不同，`Swoole`中，我们的服务程序是使用命令行挂起的

上面的代码其实在内部实现了一个Http服务功能，而不是通过`php-fpm`去输出给`nginx`服务器

`Swoole`中，`echo`之类输出了是直接将结果发送到操作系统对应的`stdout`上

服务输出则是直接通过`Swoole`代码中的服务回调参数上的`response`对象来进行服务流输出过程

类似`Java`中`System.out.println()`

### HTTP、TCP、UDP服务

```php
/* object(Swoole\Http\Request)#7 (9) {
    ["fd"]=>    // fd唯一标识符
    int(2)
    ["streamId"]=>
    int(0)
    ["header"]=>
    array(7) {
      ["host"]=>
      string(18) "192.168.3.200:9501"
      ["connection"]=>
      string(10) "keep-alive"
      ["user-agent"]=>
      string(111) "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36"
      ["accept"]=>
      string(64) "image/avif..."
      ["referer"]=>
      string(34) "http://192.168.3.200:9501/http.php"
      ["accept-encoding"]=>
      string(13) "gzip, deflate"
      ["accept-language"]=>
      string(14) "zh-CN,zh;q=0.9"
    }
    ["server"]=>
    array(10) {
      ["request_method"]=>
      string(3) "GET"
      ["request_uri"]=>
      string(12) "/favicon.ico"
      ["path_info"]=>
      string(12) "/favicon.ico"
      ["request_time"]=>
      int(1764403518)
      ["request_time_float"]=>
      float(1764403518.047377)
      ["server_protocol"]=>
      string(8) "HTTP/1.1"
      ["server_port"]=>
      int(9501)
      ["remote_port"]=>
      int(49471)
      ["remote_addr"]=>
      string(13) "192.168.3.233"
      ["master_time"]=>
      int(1764403518)
    }
    ["cookie"]=>
    NULL
    ["get"]=>   // get post请求参数
    NULL
    ["files"]=>
    NULL
    ["post"]=>
    NULL
    ["tmpfiles"]=>
    NULL
  } */


var_dump($_REQUEST);
/* array(0) {
} */


var_dump($_SERVER);
/* array(44) { // 输出命令行信息
    ["SHELL"]=>
    string(9) "/bin/bash"
    ["COLORTERM"]=>
    string(9) "truecolor"
    ["TERM_PROGRAM_VERSION"]=>
    string(6) "1.96.4"
    ["PWD"]=>
    string(18) "/var/www/html/dist"
    ["LOGNAME"]=>
    string(4) "root"
    ["XDG_SESSION_TYPE"]=>
    string(3) "tty"
    ["VSCODE_GIT_ASKPASS_NODE"]=>
    string(92) "/root/.vscode-server/cli/servers/Stable-cd4ee3b1c348a13bafd8f9ad8060705f6d4b9cba/server/node"
    ["MOTD_SHOWN"]=>
    string(3) "pam"
    ["HOME"]=>
    string(5) "/root"
    ["LANG"]=>
    string(11) "en_US.UTF-8"
    ["LS_COLORS"]=>
    string(1508) "... xspf=00;36:"
    ["SSL_CERT_DIR"]=>
    string(18) "/usr/lib/ssl/certs"
    ["GIT_ASKPASS"]=>
    string(118) "/root/.vscode-server/cli/servers/Stable-cd4ee3b1c348a13bafd8f9ad8060705f6d4b9cba/server/extensions/git/dist/askpass.sh"
    ["SSH_CONNECTION"]=>
    string(36) "192.168.3.233 53734 192.168.3.200 22"
    ["VSCODE_GIT_ASKPASS_EXTRA_ARGS"]=>
    string(0) ""
    ["LESSCLOSE"]=>
    string(23) "/usr/bin/lesspipe %s %s"
    ["XDG_SESSION_CLASS"]=>
    string(4) "user"
    ["TERM"]=>
    string(14) "xterm-256color"
    ["LESSOPEN"]=>
    string(22) "| /usr/bin/lesspipe %s"
    ["USER"]=>
    string(4) "root"
    ["VSCODE_GIT_IPC_HANDLE"]=>
    string(38) "/run/user/0/vscode-git-8ed6450347.sock"
    ["SHLVL"]=>
    string(1) "1"
    ["XDG_SESSION_ID"]=>
    string(2) "16"
    ["XDG_RUNTIME_DIR"]=>
    string(11) "/run/user/0"
    ["SSL_CERT_FILE"]=>
    string(38) "/usr/lib/ssl/certs/ca-certificates.crt"
    ["SSH_CLIENT"]=>
    string(22) "192.168.3.233 53734 22"
    ["VSCODE_GIT_ASKPASS_MAIN"]=>
    string(123) "/root/.vscode-server/cli/servers/Stable-cd4ee3b1c348a13bafd8f9ad8060705f6d4b9cba/server/extensions/git/dist/askpass-main.js"
    ["XDG_DATA_DIRS"]=>
    string(50) "/usr/local/share:/usr/share:/var/lib/snapd/desktop"
    ["BROWSER"]=>
    string(110) "/root/.vscode-server/cli/servers/Stable-cd4ee3b1c348a13bafd8f9ad8060705f6d4b9cba/server/bin/helpers/browser.sh"
    ["PATH"]=>
    string(201) "/root/.vscode-server/cli/servers/Stable-cd4ee3b1c348a13bafd8f9ad8060705f6d4b9cba/server/bin/remote-cli:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/snap/bin"
    ["DBUS_SESSION_BUS_ADDRESS"]=>
    string(25) "unix:path=/run/user/0/bus"
    ["OLDPWD"]=>
    string(20) "/root/.vscode-server"
    ["TERM_PROGRAM"]=>
    string(6) "vscode"
    ["VSCODE_IPC_HOOK_CLI"]=>
    string(64) "/run/user/0/vscode-ipc-7c99e38f-0ecb-457d-8857-671c437d271f.sock"
    ["_"]=>
    string(12) "/usr/bin/php"
    ["PHP_SELF"]=>
    string(8) "http.php"
    ["SCRIPT_NAME"]=>
    string(8) "http.php"
    ["SCRIPT_FILENAME"]=>
    string(8) "http.php"
    ["PATH_TRANSLATED"]=>
    string(8) "http.php"
    ["DOCUMENT_ROOT"]=>
    string(0) ""
    ["REQUEST_TIME_FLOAT"]=>
    float(1764403514.077407)
    ["REQUEST_TIME"]=>
    int(1764403514)
    ["argv"]=>
    array(1) {
        [0]=>
        string(8) "http.php"
    }
    ["argc"]=>
    int(1)
    } */

```

传统的全局变量如`$_REQUEST`、`$_SERVER`、`$_COOKIE`、`$_GET`、`$_POST`、`$_FILES`、`$_SESSION`等都无效

原因：

- 进程间隔
- 常住进程可能会导致内存泄漏

可以在`$request`参数中获取

#### HTTP

```php
<?php
$http = new Swoole\Http\Server('0.0.0.0', 9501);
$i = 1; // 放在监听函数外，是一个针对当前PHP文件的全局变量
$http->set([
    'worker_num'=>2,    // 启用两个Worker进程，即工作进程
]);

echo "=== 服务启动 ===\n";
echo "Master PID: " . posix_getpid() . "\n";

$http->on('WorkerStart', function ($server, $workerId) {
    echo "Worker #{$workerId} started, PID: " . posix_getpid() . ", PPID: " . posix_getppid() . "\n";
});

$http->on('ManagerStart', function ($server) {
    echo "Manager started, PID: " . posix_getpid() . "\n";
});

$http->on('Request', function ($request, $response) {
    global $i;
    // ========== 先判断 favicon，再计数,/favicon.ico请求会计数时也加1 ==========
    if ($request->server['request_uri'] == '/favicon.ico') {
        $response->status(404);
        $response->end();
        return;
    }
    $response->end($i++);
});

$http->start();

root@ubuntu:/var/www/html/dist# php http.php 
=== 服务启动 ===
Master PID: 32537
Manager started, PID: 32537
Worker #0 started, PID: 32538, PPID: 32537
Worker #1 started, PID: 32539, PPID: 32537
32537 (Master + Manager 合并)	// 主进程，以下几种情况，主进程和管理进程会合并，1.没有Task进程 2. Worker数量较少 3. Swoole版本优化
  ├─ 32538 (Worker #0)		// 子进程
  └─ 32539 (Worker #1)		// 子进程
```

```bash
ps -ef | grep php
root       32537   30033  0 08:56 pts/1    00:00:00 php http.php
root       32538   32537  0 08:56 pts/1    00:00:00 php http.php
root       32539   32537  0 08:56 pts/1    00:00:00 php http.php
```

#### TCP

```php
<?php
$server = new Swoole\Server("0.0.0.0", 9503);
// 监听连接进入事件
$server->on("Connect", function ($server, $fd) {
    echo "Client: Connect.\n";
});
// 监听数据接收时间
$server->on("Receive", function ($server, $fd, $reactor_id, $data) {
    $server->send($fd, "Server TCP:". $data);
});
// 监听连接关闭事件
$server->on("Close", function ($server, $fd) {
    echo "Client: Close.\n";
});
$server->start();
// php tcp.php 启动tcp服务器
// Connect Receive Close TCP三次握手
```

```bash
telnet 127.0.0.1 9503
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.	# ctrl+]退出
hello					   # 输入
Server TCP:hello
# ctrl+] 退出
telnet> quit
Connection closed.

# 服务端打印
php tcp.php 
Client: Connect.
Client: Close.
```

#### UDP

```php
<?php
$server = new Swoole\Server("0.0.0.0",9504, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);   // SWOOLE_PROCESS类型模式，默认SWOOLE_SOCK_TCP
// 监听数据接收事件
$server->on("Packet", function ($server, $data, $clientInfo){
    var_dump($clientInfo);
    $server->sendto($clientInfo["address"], $clientInfo["port"], "Server UDP: ".$data);
});

$server->start();

// php udp.php
```

```bash
nc -uv 127.0.0.1 9504
Connection to 127.0.0.1 9504 port [udp/*] succeeded!
Server UDPXServer UDPXServer UDPXServer UDPXServer UDPX
Server UDP
hello
Server UDP: hello
world
Server UDP: world

# 服务器端打印
...
array(5) {
  ["server_socket"]=>
  int(4)
  ["dispatch_time"]=>
  float(1764466916.421477)
  ["server_port"]=>
  int(9504)	
  ["address"]=>
  string(9) "127.0.0.1"
  ["port"]=>
  int(36480)	# 客户端连接UDP开辟端口 服务器返回客户端的临时端口
}
```

### TCP、UDP服务客户端

#### TCP

Swoole中，有同步阻塞客户端和协程客户端两种类型的客户端

**同步阻塞**，传统开发中编写代码，正常按照前后关系顺序执行的代码，从上往下执行
前面的代码没有执行完，后面的代码不会执行
如果中间遇到函数，则会通过类似栈的处理方式进行函数中进行处理
从本质上，其实面向对象这种编程方式有部分跳出这种线性执行代码的模式，但它还是同步执行

而多线程、协程脱离了同步阻塞

```php
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
```

#### UDP

```php
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
```



### WebSocket服务

Web应用，主流是HTTP、TCP、UDP这类应用
随HTML5成为主流，WebSocket应用日渐丰富
之前后台做消息通知之类应用，使用JQuery进行Ajax轮询，对应后台问题不大
但前段页面类似功能，如客服功能就费劲

WebSocket建立一个持久的长链接，不许像轮询一样不停发送HTTP请求，能够有效节省服务器资源

```php
// server/ws.php
<?php
//创建WebSocket Server对象，监听0.0.0.0:9502端口。
$ws = new Swoole\WebSocket\Server('0.0.0.0', 9502);

// 监听WebSocket连接打开事件
$ws->on('Open', function ($ws, $request) {
    while (true) {
        $time = date("Y-m-d H:i:s");
        echo "[ {$time} ], Message: {$request->fd} is in!\n";
        $ws->push($request->fd, "hello, welcome!{$time}\n");    // $request->fd 句柄，标识客户端的一个标志，每10秒发送一条信息，模拟后台通知信息
        Swoole\Coroutine::sleep(10);    // php原生函数会直接赞同整个进程的执行，事件内部不同协程来处理请求，使用Swoole提供休眠函数，针对当前协程休眠
    }
});

// 监听WebSocket消息事件
$ws->on('Message', function ($ws, $frame) {
    $time = date("Y-m-d H:i:s");
    echo "Message: {$frame->data}\n";   // data获取前端发送信息
    $ws->push($frame->fd, "[ {$time} ] Server: {$frame->data}");    // 向fd唯一标识符push信息，第二个参数发挥前端相同信息
});

// 监听WebSocket连接关闭事件
$ws->on('Close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();
// 启动 php ws.php
```

```html
<!-- client/ws.html --> 
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>swoole-cli demo</title>
</head>
<body>
    <input type="text" id="txt" />
    <button type="submit" onclick="send()">提交</button>
    <p id="response"></p>
    <script>
        // 自动使用当前页面的 IP
        var wsServer = 'ws://'+ window.location.hostname +':9502';
        var websocket = new WebSocket(wsServer);
        websocket.onopen = function (evt) {
            console.log("Connected to WebSocket server.");
        };

        websocket.onclose = function (evt) {
            console.log("Disconnected");
        };

        websocket.onmessage = function (evt) {
            console.log('Retrieved data from server: ' + evt.data);
            document.getElementById("response").innerHTML += evt.data+'<br/>';
        };

        websocket.onerror = function (evt, e) {
            console.log('Error occured: ' + evt.data);
        };

        function send() {
            websocket.send(document.getElementById("txt").value);
        }
    </script>
</body>

</html>
<!-- 浏览器访问 http://192.168.3.200/client/ws.html -->
```

### 异步任务

`HTTP`服务中，给服务设置`worker_num`属性，设置工作者进程的设置
通过`ps -ef | grep php`命令查看运行的程序多了几个进程，`Worker`其实就是一种子进程，或称工作进程

**Task进程**

可以把`Task`看做`Worker`新开出的一种用于执行耗时操作的进程。一般用于发送广播、邮件等，这些长耗时操作可能带来进程阻塞，会影响服务的执行效率

类似`Java`中的`Thread`线程（`Swoole`中，`Task`被标明为进程）

```php
<?php
$http = new Swoole\Http\Server('0.0.0.0', 9501);
$http->set([
    "task_worker_num"=> 4,
]);

$http->on("Request", function ($request, $response) use( $http) {
    echo "接受了了请求", PHP_EOL;
    $response->header("Content-Type","text/html; charset=utf-8");

    $http->task("发送邮件");
    $http->task("发送广播");
    $http->task("执行队列");

    $http->task("发送邮件2");
    $http->task("发送广播2");
    $http->task("执行队列2");

    $response->end("<h1>Hello Swoole.# ".rand(1000,9999)."</h1>");
    
});

$http->on("Task", function ($serv, $task_id, $reactor_id, $data) {
    $sec = rand(1, 5);

    echo "New AsyncTask[id={$task_id}] sleep sec: {$sec}".PHP_EOL;
    sleep($sec);
    $serv->finish($data." -> OK");
});
$http->on("Finish", function ($serv, $task_id, $data) {
    echo "AsyncTask[{$task_id}] Finish: {$data}".PHP_EOL;
});
echo "服务启动", PHP_EOL;
$http->start();

// 浏览器访问 http://192.168.3.200:9501/client/task.php
// 服务端打印
root@ubuntu:/var/www/html/dist# php server/task.php 
服务启动
接受了了请求
New AsyncTask[id=1] sleep sec: 3
New AsyncTask[id=2] sleep sec: 5
New AsyncTask[id=3] sleep sec: 4
New AsyncTask[id=0] sleep sec: 5
New AsyncTask[id=5] sleep sec: 2
AsyncTask[1] Finish: 发送广播 -> OK
AsyncTask[3] Finish: 发送邮件2 -> OK
New AsyncTask[id=4] sleep sec: 1
AsyncTask[0] Finish: 发送邮件 -> OK
AsyncTask[2] Finish: 执行队列 -> OK
AsyncTask[5] Finish: 执行队列2 -> OK
AsyncTask[4] Finish: 发送广播2 -> OK
```

`Task`任务需要监听两个事件

- `Task`事件
  用来处理任务，可以根据传递过来的`$data`内容进行处理如传递过来一个Json字符串，包含各类信息，根据Json数据内容进行后续处理

- `Finish`事件

  监听任务结束，当执行任务结束后，调用这个事件回调，进行后续处理

如一般秒杀流量非常大，在请求这个页面之后，马上返回一个秒杀请求已发送，等待秒杀结果的页面，然后开启一个任务去查询库存，如果查询到有库存，开始下单，下单成功发送邮件、通知等操作。
这一系列操作要多次查库、写库，可能非常慢，通过后台异步任务去完成，前端只是返回一个等待页面，并开始`WebSocket`监听消息，后台处理完成后发送`WebSocket`通知前端秒杀成功还是失败

```php
<?php
$http = new Swoole\Http\Server('0.0.0.0', 9501);
$http->set([
    "worker_num"=> 1,
    "task_worker_num"=> 1,
]);
...
ps -ef | grep php   
root       42675   30033  0 04:35 pts/1    00:00:00 php server/task.php	// Master+manager合并
root       42676   42675  0 04:35 pts/1    00:00:00 php server/task.php // Worker
root       42677   42675  0 04:35 pts/1    00:00:00 php server/task.php // Task Worker
```

### Redis服务器

不是连接`Redis`服务器的`PHP Redis`客户端

服务端是一个可以提供服务的应用，如熟悉的`redis-server`

`Swoole`中，`Redis`服务端是基于`Redis`协议的服务器程序，可以使用`Redis`客户端连接这个服务

```php
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
```

```bash
root@ubuntu:/var/www/html/dist# redis-cli -p 9501
127.0.0.1:9501> SADD b 1 2 3
(integer) 3
127.0.0.1:9501> set a 123
OK
127.0.0.1:9501> get a
"123"
127.0.0.1:9501> SMEBERS b
(error) ERR unknown command
127.0.0.1:9501> SMEMBERS b
1) "1"
2) "2"
3) "3"
```



## Hypef

Hypef是基于Swoole4.3实现的高性能，高灵性的PHP协程架构，内置协程服务器及大量常用组件、性能较传统基于PHP-FPM的架构有质的提升，提供超高性能的同时，也保持及其灵活的可扩展性，标准组件均基于PSR标准实现，基于强大的依赖注入设计。保证绝大部分组件或类都是可替换与可复用

