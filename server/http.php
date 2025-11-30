<?php
$http = new Swoole\Http\Server('0.0.0.0', 9501);
// $http->on('Request', function($request, $response) {
//     echo '接收了请求', PHP_EOL;
//     var_dump($request);

//     /* object(Swoole\Http\Request)#7 (9) {
//         ["fd"]=>    // fd唯一标识符
//         int(2)
//         ["streamId"]=>
//         int(0)
//         ["header"]=>
//         array(7) {
//           ["host"]=>
//           string(18) "192.168.3.200:9501"
//           ["connection"]=>
//           string(10) "keep-alive"
//           ["user-agent"]=>
//           string(111) "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36"
//           ["accept"]=>
//           string(64) "image/avif..."
//           ["referer"]=>
//           string(34) "http://192.168.3.200:9501/http.php"
//           ["accept-encoding"]=>
//           string(13) "gzip, deflate"
//           ["accept-language"]=>
//           string(14) "zh-CN,zh;q=0.9"
//         }
//         ["server"]=>
//         array(10) {
//           ["request_method"]=>
//           string(3) "GET"
//           ["request_uri"]=>
//           string(12) "/favicon.ico"
//           ["path_info"]=>
//           string(12) "/favicon.ico"
//           ["request_time"]=>
//           int(1764403518)
//           ["request_time_float"]=>
//           float(1764403518.047377)
//           ["server_protocol"]=>
//           string(8) "HTTP/1.1"
//           ["server_port"]=>
//           int(9501)
//           ["remote_port"]=>
//           int(49471)
//           ["remote_addr"]=>
//           string(13) "192.168.3.233"
//           ["master_time"]=>
//           int(1764403518)
//         }
//         ["cookie"]=>
//         NULL
//         ["get"]=>   // get post请求参数
//         NULL
//         ["files"]=>
//         NULL
//         ["post"]=>
//         NULL
//         ["tmpfiles"]=>
//         NULL
//       } */


//     // var_dump($_REQUEST);
//     /* array(0) {
//     } */


//     // var_dump($_SERVER);
//     /* array(44) { // 输出命令行信息
//         ["SHELL"]=>
//         string(9) "/bin/bash"
//         ["COLORTERM"]=>
//         string(9) "truecolor"
//         ["TERM_PROGRAM_VERSION"]=>
//         string(6) "1.96.4"
//         ["PWD"]=>
//         string(18) "/var/www/html/dist"
//         ["LOGNAME"]=>
//         string(4) "root"
//         ["XDG_SESSION_TYPE"]=>
//         string(3) "tty"
//         ["VSCODE_GIT_ASKPASS_NODE"]=>
//         string(92) "/root/.vscode-server/cli/servers/Stable-cd4ee3b1c348a13bafd8f9ad8060705f6d4b9cba/server/node"
//         ["MOTD_SHOWN"]=>
//         string(3) "pam"
//         ["HOME"]=>
//         string(5) "/root"
//         ["LANG"]=>
//         string(11) "en_US.UTF-8"
//         ["LS_COLORS"]=>
//         string(1508) "... xspf=00;36:"
//         ["SSL_CERT_DIR"]=>
//         string(18) "/usr/lib/ssl/certs"
//         ["GIT_ASKPASS"]=>
//         string(118) "/root/.vscode-server/cli/servers/Stable-cd4ee3b1c348a13bafd8f9ad8060705f6d4b9cba/server/extensions/git/dist/askpass.sh"
//         ["SSH_CONNECTION"]=>
//         string(36) "192.168.3.233 53734 192.168.3.200 22"
//         ["VSCODE_GIT_ASKPASS_EXTRA_ARGS"]=>
//         string(0) ""
//         ["LESSCLOSE"]=>
//         string(23) "/usr/bin/lesspipe %s %s"
//         ["XDG_SESSION_CLASS"]=>
//         string(4) "user"
//         ["TERM"]=>
//         string(14) "xterm-256color"
//         ["LESSOPEN"]=>
//         string(22) "| /usr/bin/lesspipe %s"
//         ["USER"]=>
//         string(4) "root"
//         ["VSCODE_GIT_IPC_HANDLE"]=>
//         string(38) "/run/user/0/vscode-git-8ed6450347.sock"
//         ["SHLVL"]=>
//         string(1) "1"
//         ["XDG_SESSION_ID"]=>
//         string(2) "16"
//         ["XDG_RUNTIME_DIR"]=>
//         string(11) "/run/user/0"
//         ["SSL_CERT_FILE"]=>
//         string(38) "/usr/lib/ssl/certs/ca-certificates.crt"
//         ["SSH_CLIENT"]=>
//         string(22) "192.168.3.233 53734 22"
//         ["VSCODE_GIT_ASKPASS_MAIN"]=>
//         string(123) "/root/.vscode-server/cli/servers/Stable-cd4ee3b1c348a13bafd8f9ad8060705f6d4b9cba/server/extensions/git/dist/askpass-main.js"
//         ["XDG_DATA_DIRS"]=>
//         string(50) "/usr/local/share:/usr/share:/var/lib/snapd/desktop"
//         ["BROWSER"]=>
//         string(110) "/root/.vscode-server/cli/servers/Stable-cd4ee3b1c348a13bafd8f9ad8060705f6d4b9cba/server/bin/helpers/browser.sh"
//         ["PATH"]=>
//         string(201) "/root/.vscode-server/cli/servers/Stable-cd4ee3b1c348a13bafd8f9ad8060705f6d4b9cba/server/bin/remote-cli:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/snap/bin"
//         ["DBUS_SESSION_BUS_ADDRESS"]=>
//         string(25) "unix:path=/run/user/0/bus"
//         ["OLDPWD"]=>
//         string(20) "/root/.vscode-server"
//         ["TERM_PROGRAM"]=>
//         string(6) "vscode"
//         ["VSCODE_IPC_HOOK_CLI"]=>
//         string(64) "/run/user/0/vscode-ipc-7c99e38f-0ecb-457d-8857-671c437d271f.sock"
//         ["_"]=>
//         string(12) "/usr/bin/php"
//         ["PHP_SELF"]=>
//         string(8) "http.php"
//         ["SCRIPT_NAME"]=>
//         string(8) "http.php"
//         ["SCRIPT_FILENAME"]=>
//         string(8) "http.php"
//         ["PATH_TRANSLATED"]=>
//         string(8) "http.php"
//         ["DOCUMENT_ROOT"]=>
//         string(0) ""
//         ["REQUEST_TIME_FLOAT"]=>
//         float(1764403514.077407)
//         ["REQUEST_TIME"]=>
//         int(1764403514)
//         ["argv"]=>
//         array(1) {
//             [0]=>
//             string(8) "http.php"
//         }
//         ["argc"]=>
//         int(1)
//         } */


//     $response->header('Content-type', 'text/html;charset=utf-8');
//     $response->end('<h1>Hello Swoole .#'.rand(1000, 9999).'</h1>');
// });


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
    // ========== 先判断 favicon，再计数 ==========
    if ($request->server['request_uri'] == '/favicon.ico') {
        $response->status(404);
        $response->end();
        return;
    }
    $response->end($i++);
});


// echo '服务启动', PHP_EOL;
$http->start();