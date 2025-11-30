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



### 网络协议

现代网络发送一张几十兆高清图片相当于寄一栋大楼

寄大楼 -> 发照片要处理问题

- 需要知道目标地址 -> 寻址和路由
- 需要一条到达目标地址的路 -> 数据链路
- 需要将大楼拆分成包装箱能存放的大小 ->分片
- 需要将每一部分编号 -> 序列码
- 需要将包装箱装车 -> 封装
- 车队运输时可能堵车 -> 阻塞控制

到达目的地址（数据包到达）

- 检查每一车是否完整 -> 错误检测和校正
- 处理在运输路上丢失和损毁的部分 -> 数据重发，冗余恢复
- 拆除包装，将每一部分重新组装起来 -> 重组

如何查找目的地址
如何选择运输路线
如何拆分和重建大楼
需要一些流程规范、运输指南、使用说明，称为协议

协议有很多种
不同的协议处理不同层次的问题
发送方和接收方要使用相同的协议，才能还原数据

网络协议实现最主要有两个

- ISO颁布OSI七层模型

  越往上处理问题越单一，越往下越基础

  - 主机层

    - 应用层

      7层或L7，网络进程到应用程序
      针对特定应用规定各层协议、时序、表示等进行封装
      在端系统中用软件来实现，如HTTP

    - 表示层

    - 会话层

    - 传输层

      4层或L4，在网络各个节点之间可靠的分发数据包
      所有传输遗留问题复用；流量；可靠
      传输数据段 Segment

  - 媒介层

    - 网络层

      在网络的各个节点之间进行地址分配、路由和分发报文（不可靠）
      路由；拥塞控制
      传输数据包 Package

    - 数据链路层

      可靠的点对点数据直链
      检错和纠错；多路访问；寻址
      传输数据帧 Frame（Bit组合，一片一片传）

    - 物理层 
      不可靠的点对点数据直链，物理链路电信号易受干扰
      传输 Bit

    4层和7层是OSI协议中最重要两个层

    

  缺点：

  - 进展缓慢
  - 太过复杂
  - 收费

  市场被TCP/IP取代

  

- TCP/IP

  一系列协议，四层

  - 应用程序

    - 应用层

      HTTP、DNS、FTP、SSH、TELNET

  - 操作系统

    - 传输层

      UDP、TCP协议

    - 网络互连层

      ARP、IP、ICMP协议

  - 设备驱动网络接口

    - 网络接口层

      以太网、Wifi、PPP协议

    - （硬件）

![image-20251130193716840](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2Fa7562c61afc9f8954443ef93a507bf2e.png)

ARP：通过IP反查MAC协议，相当于翻译器

![image-20251130193914461](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2Ff25784cf77b0e0a7bc743455fca7613d.png)



以太网，主要解决怎么传的问题

IP协议，主要解决往哪传问题

UDP协议/TCP协议，主要解决可靠性问题



### IP协议

####  IP地址

- 由32位二进制数组成

- 分为网络标识和主机标识两部分

- 子网掩码确定了32位里，哪些是网络标识，哪些是主机标识

  1为网络标识，0为主机标识

```
# IP地址
11000000 10101000 00000000 00000001
为方便转为十进制：192.168.0.1

# 子网掩码
11111111 11111111 11111111 00000000
255.255.255.0

11111111 11111111 11111111 00000000
11000000 10101000 00000000 00000001
__________________________ ________
前24位全部为1，网络标识		后八位全部为0，主机标识
或写成 192.168.0.1/24 ip地址/子网掩码位数
```

#### 路由控制

同一内网数据传输

![image-20251130202621852](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2Faa81bf50dc58eb403f1071c0b85d1f57.png)

网络之间的数据传输

![动画](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2F06237045903383cbe1590a34fcf4f6b5.gif)

#### IP分片和重组

- 不同的网络最大传输单位（MTU）大小不同
- IP协议是这些网络的上层封装，它对此进行抽象
- 路径发现MTU会在发送数据帧超过网络MTU时自动调整并重发数据
- IP报文由路由器进行分片，目标主机进行重组



数据包

```
发送端MAC地址/接收端MAC地址
以太网类型/校验数据FCS

发送端IP地址/接收端IP地址
协议类型

源端口/目标端口号
```

数据帧

![数据帧](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2Fa93432d92487d3f50abee5587132dab6.png)

网卡

![image-20251130200659454](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2Fdeeaac809cb46eccba951f79ba50e6fc.png)

交换机根据MAC地址转发数据 



#### IP相关协议

- ICMP 

  确认IP包是否成功送达通知发送过程中IP包被废弃的原因、改善网络设置

- ARP

  通过IP地址查询MAC地址

- DHCP

  动态分配IP地址

- DNS

  通过域名查询对应的IP地址

- NAT

  将外网IP和端口映射到内网机器上



### UDP协议

#### 端口

常用端口

- 21 FTP 文件传输服务
- 22 SSH 命令行远程登录
- 25 SMTP 邮件发送服务
- 80 HTTP 网站服务
- 110 POP3 邮件接收服务
- 139 SMB SAMBA共享
- 143 IMAP 邮件接收服务
- 443 HTTP 加密网站服务
- 3306 MySQL 数据库服务
- 3389 RDP 远程桌面服务
- 6379 Redis 缓存服务器
- 8080 Proxy 代理服务器

#### 校验和

UDP header

![image-20251130204533862](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2Ff5d60b5b4f1706f93a143bfd563cbbea.png)

### TCP协议

#### 序列号机制和三次握手

TCP header

![image-20251130204810407](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2Fda784639e5aebbf4785d542e2ef1bd42.png)

校验和的值：头部和数据部分的和，再对其求反

客户端和服务端都会维护一个序列码 Client seq=1，2、Server seq=24（此时连接已关闭）

![image-20251130204958090](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2Fddff29e50ae34cb23fd2e2654c105311.png)

#### 滑动窗口

为提升序列机制和三次握手效率，提出滑动窗口，可以理解为并行

![image-20251130205347365](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2F9653f0de209c2db2831df79eab05fe46.png)

#### 拥塞控制

![image-20251130205510734](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2F100c9ab1aed604dedc10daa38bd39cf2.png)



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

# 安装composer
apt install composer
# 安装Predis
composer require predis/predis
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

PHP一般只写Web程序，对网络通信架构、异步、多线程、协程，多进程这些特性不够完善

2010年底，韩天峰为实现TCP Socket Server实现SMTP协议接收数据需求，并开源该系统，为PHP从单纯的Web开发扩展到更大的空间

Swoole基于C语言面向生成环境的PHP异步网络通信引擎，使PHP开发人员可以编写高性能的异步并发TCP、UDP、Unix Socket、HTTP，WebSocket服务。Swoole可以广泛应用于互联网、移动通信、企业软件、云计算、网络游戏、物联网IOT、车联网、智能家居等领域

特性

PHP的协程高性能网络通信引擎，使用C/C++语言编写PHP扩展，提供了多种通信协议的网络服务器和客户端模块

- TCP、UDP、UnixSock 服务器端
- Http、WebSocket、Http2.0 服务器端
- 协程TCP、UDP、UnixSock
- 协程MySQL
- 协程Redis
- 协程Http、WebSocket
- 协程Http2
- AsyncTask
- 毫秒定时器
- 协程文件读写

Swoole4支持完整的协程编程模式，可以使用完全同步的代码实现异步程序

PHP代码无需额外增加任何关键词，底层自动进行协程调度，实现异步IO

异步回调模块已经过时，在4.3版本中移除了异步模块，使用Coroutine协程模块（协程实现异步）

Workerman与Swoole区别

- workerman 纯PHP，PHP框架 
  Swoole C语言，作为php扩展
- Workerman 多进程
  Swoole 协程、多进程、多线程
- Swoole不使用Libevent（C++事件通知库，处理I/O事件、信号事件、定时事件），不依赖PHP的stream、sockets、pcntl、posix、sysvmg等扩展
- Swoole性能优于Workerman



### Server

```PHP
Server(string $host, int $port=0, int $mode=SWOOLE_PROCERSS, int $sock_type=SWOOLE_SOCK_TCP);
```

- `$host` 主机

  `IPv4`使用`127.0.0.1`监控本机，`0.0.0.0`监控任何地址

  `IPv6`使用`::1`监听本机，`::`（相当于`0:0:0:0:0:0:0:0`）监听所有地址

- `$port` 端口

  `$sock_type`为`UnixSocket Stream、Dgram`，此参数忽略

  监听小于`1024`端口需要`root`权限

- `$mode` 运行模式

  - `SWOOLE_PROCESS` 多进程模式（默认）
    最复杂的方式，用了大量的进程间通信、进程管理机制

  - `SWOOKE_BASE` 基础模式，单线程模式
    传统的异步非阻塞`Server`，如果客户端连接之间不需要交互，可以使用BASE模式，如`Memcache`、`Http`服务器等

- `$sock_type` 指定`Socket`类型

  `TCP`、`UDP`、`TCP6`、`UDP6`、`UnixSocket`、`Stream/Dgram` 6种

  `SWOOLE_SOCK_TCP`（默认）	`SWOOLE_SOCK_TCP6`	`SWOOLE_SOCK_UDP`

  `SWOOLE_SOCK_UDP6`	`SWOOLE_SOCK_DGRAM`	`SWOOLE_SOCK_STREAM`

```bash
# 安装进程管理增强包，传统网络工具
apt install psmisc net-tools -y
# 查看进程树
pstree -p 进程号
# 查看谁在用这个端口
fuser 9501/tcp
```

```php
<?php
// $server = new Swoole\Server("0.0.0.0",8888, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);   // 4.x版本后只支持命名空间，不再支持下划线风格
$server = new Swoole\Server("0.0.0.0",8888, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
// var_dump($server);

// $server->on("Receive", function(){});	// TCP回调
$server->on("Packet", function(){});	// UDP回调

$server->start();

// 命令行查看端口
netstat -nltup | grep 8888	// TCP
netstat -nultup | grep 8888	// UDP
```



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

`Swoole`协程环境不使用`phpredis`扩展

- 阻塞`IO`问题 - 传统`phpredis`扩展是同步阻塞的，会阻塞整个进程
- 协程冲突 - 在`Swoole`协程中使用会导致调度失效

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

## 进程、线程、协程

### 进程、线程、协程

#### 进程、线程

进程是一个具有一定独立功能的程序在一个数据集上的一次动态执行的过程，是操作系统进行资源分配和调度的一个独立单位（由CPU调度），是应用程序运行的载体

```bash
root@ubuntu:/var/www/html/dist# ps -ef | grep php
root       51867       1  0 09:53 ?        00:00:01 php-fpm: master process (/etc/php/8.1/fpm/php-fpm.conf)	# php-fpm主进程
www-data   51870   51867  0 09:53 ?        00:00:00 php-fpm: pool www
www-data   51871   51867  0 09:53 ?        00:00:00 php-fpm: pool www
# 每个php-fpm均为一个进程
```

线程是程序执行中一个单一的顺序控制流程，是程序执行流的最小单位，是处理器调度和分派的基本单位，一个进程可以有多个线程

#### 进程线程区别

- 线程是程序执行的最小单位，而进程是操作系统分配资源（分配运算力，内存空间）的最小单位
- 一个进程由一或多个线程组成，线程是一个进程中代码的不同执行路线
- 进程之间互相独立，但同一进程下的各个线程之间共享程序的内存空间（包括代码段，数据集，堆等）及一些进程级的资源（如打开文件和信号等），某进程内的线程在其他进程不可见
- 调度和切换：线程上下文切换比进程上下文要快
- 线程天生的共享内存空间，线程间的通信更简单，避免进程IPC（进程间通信）引入新的复杂度
- 进程开销大，线程开销小



#### PHP实现多进程

pcntl PHP多进程扩展 

```bash
# 验证是否安装
php -m | grep pcntl
```

![image-20251130221749513](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2F261624e0cbde530139d0e301288e076a.png)

```php
$pid = pcntl_fork();    // fork是创建一个子进程，父进程和子进程都从fork位置开始向下继续执行
// 父进程在执行过程中，得到fork返回值为子进程号
// 子进程执行过程中，得到的值为0
// echo $pid.PHP_EOL;
/* 
php pcntl_fork.php 
54216   // 父进程执行结果：子进程号
0       // 子进程执行结果：0
*/

if ($pid > 0) {
    echo "父进程".PHP_EOL;
} else if($pid == 0) {
    echo "子进程".PHP_EOL;
}
```

```php
// 模拟多进程，先打印111,10秒后打印222
if ($pid = pcntl_fork() == 0) {
    sleep(10);
    echo "222".PHP_EOL;
}
if($pid == 0) {
    if(pcntl_fork() == 0) {
        echo "111".PHP_EOL;
    }
}
```

```php
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
```

#### 进程状态

![image-20251130224150008](https://cdn.jsdelivr.net/gh/lizzzy/picBed@master/img/20251130%2Fc96c15934141da208d579c039c488e10.png)

```bash
root@ubuntu:/var/www/html/dist# top
top - 14:44:15 up 1 day, 11:21,  2 users,  load average: 0.11, 0.19, 0.17
Tasks: 164 total,   1 running, 163 sleeping,   0 stopped,   0 zombie
# 进程				运行		睡眠			停止			僵尸
```

僵尸进程：当子进程比父进程先结束，父进程又没有回收释放子进程，子进程形成一个僵尸进程



#### 进程信号

Linux中以SIG字符串开头后跟信号名称，及对应的数值（操作系统真正认识信号）

可处理

- `SIGHUP(1)` 挂起信号

  终端断开，如关闭`SSH`，或`kill -HUP 进程ID`

  让程序重新读取配置文件

- `SIGINT(2)` 中断信号

  终端`Ctrl+C`，保存数据后退出

- `SIGTERM(15)` 终止信号

  请正常退出 `kill 进程ID`

  程序可以清理资源后退出

强制信号，不可处理

- `SIGKILL(9)` 强制杀死

  `kill -9 进程ID`

  进程无法捕获，立即终止，可能会导致数据丢失

- `SIGSTOP(19)` 强制暂停

  `kill -STOP 进程ID` 或 `Ctrl+z`

  冻结，暂停进程，不可捕获

- `SIGCONT(18)` 继续执行

  `kill -CONT 进程ID` 或 `fg`命令

  恢复`SIGSTOP`暂停的进程

特殊信号

- `SIGCHLD(17)` 子进程状态改变

  子进程退出时自动发给父进程

  父进程回收子进程资源

- `SIGPIPE(13)` 管道破裂

  向已关闭的管道写数据

  防止程序崩溃

#### 多线程

PHP默认不支持多线程

pthread PHP多线程扩展，不再维护，使用parallel（需ZTS线程安全版本PHP，Ubuntu默认NTS非线程安全版本版本）或Swoole

#### 线程安全

多线程让程序变得不安分的因素，在使用多线程之前，首先要考虑吧线程安全问题

线程安全：编程术语，某个函数，函数库在多线程环境中被调用时，能够正确处理多个线程之间的共享变量，使程序功能正确完成

PHP实现线程安全主要使用TSRM机制，对全局变量和静态变量进行隔离
将全局变量和静态变量给每个线程都复制一份，各线程使用的都是主线程的一个备份
从而避免变量冲突，也就不会出现线程安全问题







### Swoole异步进程服务

### 单进程管理Process

### 进程间通信

- 管道通信

- 消息队列通信

- 进程信号通信

- 共享内存通信

  映射一段能被其他进程访问的内存，这段共享内存由一个进程创建，但多个进程都可以访问
  共享内存是最快的IPC（进程间通信）方式，针对其他进程间通信方式效率低而专门设计
  往往与其通信机制配合使用，实现进程间的同步和通信

- 套接字通信

- 第三方通信，如文件操作，mysql，redis等

### 进程池与进程管理器

### 进程同步与共享内存

## Swoole协程系统

### Swoole协程系统

### 协程应用与容器

### 协程操作系统API

### 协程间通信Channel及Wait

### 协程并发调度

### 协程连接池

### 协程服务客户端

### 一键协程化

## 毫秒定时器/TCP数据边界

### 毫秒定时器

### TCP数据边界（粘包）

## 将Laravel改成Swoole版



## Hypef

Hypef是基于Swoole4.3实现的高性能，高灵性的PHP协程架构，内置协程服务器及大量常用组件、性能较传统基于PHP-FPM的架构有质的提升，提供超高性能的同时，也保持及其灵活的可扩展性，标准组件均基于PSR标准实现，基于强大的依赖注入设计。保证绝大部分组件或类都是可替换与可复用
