<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : Daemon.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-4-15 17:13
*   Description : 
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../util.php");
require_once(dirname(__FILE__)."/events/Select.php");
require_once(dirname(__FILE__)."/lib/Timer.php");
require_once(dirname(__FILE__)."/protocols/HttpProtocol.php");

class DaemonType {
    const WORKERMAN_CONNECT_FAIL = 1;
    const WORKERMAN_SEND_FAIL = 2;
}

/**
* Daemon Base Class - Extend this to build daemons.
* @uses PHP 5.3 or Higher
* @author dongjiang.dongj
*/
class Daemon {
    
    /**
    * 状态 启动中
    */
    const STATUS_STARTING = 1;
    
    /**
    * 状态 运行中
    */
    const STATUS_RUNNING = 2;
    
    /**
    * 状态 停止
    */
    const STATUS_SHUTDOWN = 4;
    
    /**
    * 状态 平滑重启中
    */
    const STATUS_RELOADING = 8;
    
    /**
    * 给子进程发送重启命令 KILL_WORKER_TIMER_TIME 秒后
    * 如果对应进程仍然未重启则强行杀死
    */
    const KILL_WORKER_TIMER_TIME = 1;
    
    /**
    * 默认的backlog，即内核中用于存放未被进程认领（accept）的连接队列长度
    */
    const DEFAUL_BACKLOG = 1024;
    
    /**
    * udp最大包长
    */
    const MAX_UDP_PACKEG_SIZE = 65535;
    
    /**
    * worker的名称，用于在运行status命令时标记进程
    */
    public $name = 'none';
    
    /**
    * 设置当前worker实例的进程数
    */
    public $count = 1;
    
    /**
    * 设置当前worker进程的运行用户，启动时需要root超级权限
    */
    public $user = '';
    
    /**
    * 当前worker进程是否可以平滑重启 
    */
    public $reloadable = false;
    
    /**
    *   当worker进程启动时，如果设置了$onWorkerStart回调函数，则运行
    * 此钩子函数一般用于进程启动后初始化工作
    * @var callback
    */
    public $onWorkerStart = null;
    
    /**
    * 当有客户端连接时，如果设置了$onConnect回调函数，则运行
    * @var callback
    */
    public $onConnect = null;
    
    /**
    * 当客户端连接上发来数据时，如果设置了$onMessage回调，则运行
    * @var callback
    */
    public $onMessage = null;
    
    /**
    * 当客户端的连接关闭时，如果设置了$onClose回调，则运行
    * @var callback
    */
    public $onClose = null;
    
    /**
    * 当客户端的连接发生错误时，如果设置了$onError回调，则运行
    * 错误一般为客户端断开连接导致数据发送失败、服务端的发送缓冲区满导致发送失败等
    * 具体错误码及错误详情会以参数的形式传递给回调
    * @var callback
    */
    public $onError = null;
    
    /**
    * 当连接的发送缓冲区满时，如果设置了$onBufferFull回调，则执行
    * @var callback
    */
    public $onBufferFull = null;
    
    /**
    * 当链接的发送缓冲区被清空时，如果设置了$onBufferDrain回调，则执行
    * @var callback
    */
    public $onBufferDrain = null;
    
    /**
    * 当前进程退出时（由于平滑重启或者服务停止导致），如果设置了此回调，则运行
    * @var callback
    */
    public $onWorkerStop = null;
    
    /**
    * 传输层协议
    */
    public $transport = 'tcp';
    
    /**
    * 所有的客户端连接
    */
    public $connections = array();
    
    /**
    * 应用层协议，由初始化worker时指定
    * 例如 new worker('http://0.0.0.0:8080');指定使用http协议
    */
    protected $_protocol = '';
    
    /**
    * 当前worker实例初始化目录位置，用于设置应用自动加载的根目录
    */
    protected $_appInitPath = '';
    
    /**
    * 是否以守护进程的方式运行。运行start时加上-d参数会自动以守护进程方式运行
    * 例如 php start.php start -d
    */
    public $daemonize = false;

    /**
    * 重定向标准输出，即将所有echo、var_dump等终端输出写到对应文件中
    * 注意 此参数只有在以守护进程方式运行时有效
    */
    public static $stdoutFile = '/dev/null';
    
    /**
    * pid文件的路径及名称
    * 例如 Daemon::$pidFile = '/tmp/phpmock.pid';
    * 注意 此属性一般不必手动设置，默认会放到php临时目录中
    * @var string
    */
    public static $pidFile = '';
    
    /**
    * 日志目录，默认在phpmock根目录下，与Applications同级
    * 可以手动设置
    * 例如 Daemon::$logFile = '/tmp/phpmock.log';
    */
    public static $logFile = '/tmp/phpmock.log';
    
    /**
    * 全局事件轮询库，用于监听所有资源的可读可写事件
    * @var Select
    */
    public $globalEvent = null;
    
    /**
    * 主进程pid
    */
    public $_masterPid = 0;
    
    /**
    * 监听的socket
    */
    protected $_mainSocket = null;
    
    /**
    * socket名称，包括应用层协议+ip+端口号，在初始化worker时设置 
    * 值类似 http://0.0.0.0:80
    */
    protected $_socketName = '';
    
    /**
    * socket的上下文，具体选项设置可以在初始化worker时传递
    * @var context
    */
    protected $_context = null;
    
    /**
    * 所有的worker实例
    */
    protected $_workers = array();
    
    /**
    * 所有worker进程的pid
    * 格式为 [worker_id=>[pid=>pid, pid=>pid, ..], ..]
    */
    protected $_pidMap = array();
    
    /**
    * 所有需要重启的进程pid
    * 格式为 [pid=>pid, pid=>pid]
    */
    protected $_pidsToRestart = array();
    
    /**
    * 当前worker状态
    */
    protected $_status = self::STATUS_STARTING;
    
    /**
    * 所有worke名称(name属性)中的最大长度，用于在运行 status 命令时格式化输出
    */
    protected static $_maxWorkerNameLength = 12;
    
    /**
    * 所有socket名称(_socketName属性)中的最大长度，用于在运行 status 命令时格式化输出
    */
    protected static $_maxSocketNameLength = 12;
    
    /**
    * 所有user名称(user属性)中的最大长度，用于在运行 status 命令时格式化输出
    */
    protected static $_maxUserNameLength = 12;
    
    /**
    * 运行 status 命令时用于保存结果的文件名
    */
    protected $_statisticsFile = '';
    
    /**
    * 启动的全局入口文件
    * 例如 php start.php start ，则入口文件为start.php
    */
    protected $_startFile = '';
    
    /**
    * 全局统计数据，用于在运行 status 命令时展示
    * 统计的内容包括 phpmock启动的时间戳及每组worker进程的退出次数及退出状态码
    */
    protected $_globalStatistics = array(
        'start_timestamp' => 0,
        'worker_exit_info' => array()
    );

    /**
    * 设置一个mock使用的Timer
    */
    protected $_timer = null;

    /**
    * worker构造函数
    * @param string $socket_name
    * @return void
    * @author dongjiang.dongj
    */
    public function __construct($socket_name = '', $context_option = array())
    {
        // 保存worker实例
        $this->workerId = spl_object_hash($this);
        $this->_workers[$this->workerId] = $this;
        $this->_pidMap[$this->workerId] = array();

        // init Timer
        $this->_timer = new Timer();
        
        // 获得实例化文件路径，用于自动加载设置根目录
        $backrace = debug_backtrace();
        $this->_appInitPath = dirname($backrace[0]['file']);
        
        // 设置socket上下文
        if($socket_name)
        {
            $this->_socketName = $socket_name;
            if(!isset($context_option['socket']['backlog']))
            {
                $context_option['socket']['backlog'] = self::DEFAUL_BACKLOG;
            }
            $this->_context = stream_context_create($context_option);
        }
    }
    
    /**
    * 运行所有worker实例
    * @return void
    * @author dongjiang.dongj@
    */
    public function runAll()
    {
        // 初始化环境变量
        $this->init();
        // 尝试以守护进程模式运行
        $this->daemonize();
        // 初始化所有worker实例，主要是监听端口
        $this->initWorkers();
        //  初始化所有信号处理函数
        $this->installSignal();
        // 保存主进程pid
        $this->saveMasterPid();
        // 创建子进程（worker进程）并运行
        $this->forkWorkers();
    }

    /**
    * 初始化一些环境变量
    * @return void
    * @author dongjiang.dongj
    */
    public function init()
    {
        // 如果没设置$pidFile，则生成默认值
        if(empty($this->pidFile))
        {
            $backtrace = debug_backtrace();
            $this->_startFile = $backtrace[count($backtrace)-1]['file'];
            $this->pidFile = sys_get_temp_dir()."/phpmock.".str_replace('/', '_', $this->_startFile).".pid";
        }
        // 没有设置日志文件，则生成一个默认值
        if(empty(self::$logFile))
        {
            self::$logFile = dirname(__FILE__).'/tmp/phpmock.log';
        }
        // 标记状态为启动中
        $this->_status = self::STATUS_STARTING;
        // 启动时间戳
        $this->_globalStatistics['start_timestamp'] = time();
        // 设置status文件位置
        $this->_statisticsFile = sys_get_temp_dir().'/phpmock.status';
        
        // 初始化定时器
        $this->_timer->init();
    }

    /**
    * 安装信号处理函数     
    * @return void
    * @author dongjiang.dongj@
    */
    protected function installSignal()
    {
        // stop
        pcntl_signal(SIGINT,  array($this, 'signalHandler'), false);
        // reload
        pcntl_signal(SIGUSR1, array($this, 'signalHandler'), false);
        // status
        pcntl_signal(SIGUSR2, array($this, 'signalHandler'), false);
        // other to ignore 
        pcntl_signal(SIGPIPE, SIG_IGN, false);
    }

    /**
    * 为子进程重新安装信号处理函数，使用全局事件轮询监听信号
    * @return void
    * @author dongjiang.dongj@
    */
    protected function reinstallSignal()
    {
        // uninstall stop signal handler
        pcntl_signal(SIGINT,  SIG_IGN, false);
        // uninstall reload signal handler
        pcntl_signal(SIGUSR1, SIG_IGN, false);
        // uninstall  status signal handler
        pcntl_signal(SIGUSR2, SIG_IGN, false);
        // reinstall stop signal handler
        $this->globalEvent->add(SIGINT, EventInterface::EV_SIGNAL, array($this, 'signalHandler'));
        //  uninstall  reload signal handler
        $this->globalEvent->add(SIGUSR1, EventInterface::EV_SIGNAL,array($this, 'signalHandler'));
        // uninstall  status signal handler
        $this->globalEvent->add(SIGUSR2, EventInterface::EV_SIGNAL, array($this, 'signalHandler'));
    }
    
    /**
    * 信号处理函数
    * @param int $signal
    * @author dongjiang.dongj@
    */
    public function signalHandler($signal)
    {
        switch($signal)
        {
            // stop
            case SIGINT:
                self::stopAll();
                break;
            // reload
            case SIGUSR1:
                self::$_pidsToRestart = self::getAllWorkerPids();
                self::reload();
                break;
            // show status
            case SIGUSR2:
                self::printStatisticsToSTDOUT();
                break;
        }
    }

    /**
    * 创建子进程
    * @return void
    * @author dongjiang.dongj@
    */
    protected function forkWorkers()
    {
        foreach($this->_workers as $worker)
        {
            // 启动过程中需要得到运行用户名的最大长度，在status时格式化展示
            if($this->_status === self::STATUS_STARTING)
            {
                if(empty($worker->name))
                {
                    $worker->name = $worker->getSocketName();
                }
                $worker_name_length = strlen($worker->name);
                if(self::$_maxWorkerNameLength < $worker_name_length)
                {
                    self::$_maxWorkerNameLength = $worker_name_length;
                }
            }
            
            // 创建子进程
            while(count($this->_pidMap[$worker->workerId]) < $worker->count)
            {
                $this->forkOneWorker($worker);
            }
        }
    }
    
    /**
    * 创建一个子进程
    * @param Worker $worker
    * @throws Exception
    * @author dongjiang.dongj@
    */
    protected function forkOneWorker($worker)
    {
        $pid = pcntl_fork();
        // 主进程记录子进程pid
        if($pid > 0)
        {
            $this->_pidMap[$worker->workerId][$pid] = $pid;
        }
        // 子进程运行
        else if(0 === $pid)
        {
            $this->_pidMap = array();
            $this->_workers = array($worker->workerId => $worker);
            $this->_timer->delAll();
            $this->setProcessUser($worker->user);
            $worker->run();
            exit(250);
        }
        else
        {
            throw new Exception("forkOneWorker fail");
        }
    }

    /**
    * 获得 socket name
    * @return string
    * @author dongjiang.dongj@
    */
    public function getSocketName()
    {
        return $this->_socketName ? $this->_socketName : 'none';
    }

    /**
    * 运行worker实例
    * @author dongjiang.dongj@
    */
    public function run()
    {
        // 注册进程退出回调，用来检查是否有错误
        register_shutdown_function(array($this, 'checkErrors'));
        
        // 如果没有全局事件轮询，则创建一个
        if(!$this->globalEvent)
        {
            $this->globalEvent = new Select();
            // 监听_mainSocket上的可读事件（客户端连接事件）
            if($this->_socketName)
            {
                $this->globalEvent->add($this->_mainSocket, EventInterface::EV_READ, array($this, 'acceptConnection'));
            }
        }
        
        // 重新安装事件处理函数，使用全局事件轮询监听信号事件
        $this->reinstallSignal();
        
        // 用全局事件轮询初始化定时器
        $this->_timer->init($this->globalEvent);
        
        // 如果有设置进程启动回调，则执行
        if($this->onWorkerStart)
        {
            call_user_func($this->onWorkerStart, $this);
        }
        
        // 子进程主循环
        $this->globalEvent->loop();
    }

    /**
    * 检查错误
    * @return void
    * @author dongjiang.dongj@
    */
    public function checkErrors()
    {
        if(self::STATUS_SHUTDOWN != $this->_status)
        {
            $error_msg = "WORKER EXIT UNEXPECTED ";
            $errors = error_get_last();
            if($errors && ($errors['type'] == E_ERROR ||
                     $errors['type'] == E_PARSE ||
                     $errors['type'] == E_CORE_ERROR ||
                     $errors['type'] == E_COMPILE_ERROR || 
                     $errors['type'] == E_RECOVERABLE_ERROR ))
            {
                $error_msg .= self::getErrorType($errors['type']) . " {$errors['message']} in {$errors['file']} on line {$errors['line']}";
            }
            self::log($error_msg);
        }
    }

    /**
    * 获取错误类型对应的意义
    * @param integer $type
    * @return string
    * @author dongjiang.dongj@
    */
    protected static function getErrorType($type)
    {
        switch($type)
        {
            case E_ERROR: // 1 //
                return 'E_ERROR';
            case E_WARNING: // 2 //
                return 'E_WARNING';
            case E_PARSE: // 4 //
                return 'E_PARSE';
            case E_NOTICE: // 8 //
                return 'E_NOTICE';
            case E_CORE_ERROR: // 16 //
                return 'E_CORE_ERROR';
            case E_CORE_WARNING: // 32 //
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR: // 64 //
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING: // 128 //
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR: // 256 //
                return 'E_USER_ERROR';
            case E_USER_WARNING: // 512 //
                return 'E_USER_WARNING';
            case E_USER_NOTICE: // 1024 //
                return 'E_USER_NOTICE';
            case E_STRICT: // 2048 //
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR: // 4096 //
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED: // 8192 //
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED: // 16384 //
                return 'E_USER_DEPRECATED';
        }
        return "";
    }

    /**
    * 记录日志
    * @param string $msg
    * @return void
    * @author dongjiang.dongj@
    */
    protected function log($msg)
    {
        $msg = $msg."\n";
        if($this->_status === self::STATUS_STARTING || !$this->daemonize)
        {
            echo $msg;
        }
        file_put_contents(self::$logFile, date('Y-m-d H:i:s') . " " . $msg, FILE_APPEND | LOCK_EX);
    }

    /**
    * 初始化所有的worker实例，主要工作为获得格式化所需数据及监听端口
    * @return void
    * @author dongjiang.dongj@
    */
    protected function initWorkers()
    {
        foreach($this->_workers as $worker)
        {
            // 没有设置worker名称，则使用none代替
            if(empty($worker->name))
            {
                $worker->name = 'none';
            }
            // 获得所有worker名称中最大长度
            $worker_name_length = strlen($worker->name);
            if(self::$_maxWorkerNameLength < $worker_name_length)
            {
                self::$_maxWorkerNameLength = $worker_name_length;
            }
            // 获得所有_socketName中最大长度
            $socket_name_length = strlen($worker->getSocketName());
            if(self::$_maxSocketNameLength < $socket_name_length)
            {
                self::$_maxSocketNameLength = $socket_name_length;
            }
            // 获得运行用户名的最大长度
            if(empty($worker->user) || posix_getuid() !== 0)
            {
                $worker->user = $this->getCurrentUser();
            }
            $user_name_length = strlen($worker->user);
            if(self::$_maxUserNameLength < $user_name_length)
            {
                self::$_maxUserNameLength = $user_name_length;
            }
            // 监听端口
            $worker->listen();
        }
    }

    /**
    * 监听端口
    * @throws Exception
    * @author dongjiang.dongj@
    */
    public function listen()
    {
        if(!$this->_socketName)
        {
            return;
        }
        // 获得应用层通讯协议以及监听的地址
        list($scheme, $address) = explode(':', $this->_socketName, 2);
        // 如果有指定应用层协议，则检查对应的协议类是否存在
        if($scheme != 'tcp' && $scheme != 'udp')
        {
            $scheme = ucfirst($scheme);
            $this->_protocol = $scheme."Protocol"; // Protocol拼接，如：Http+Protocol
            if(!class_exists($this->_protocol))
            {
                throw new Exception("class $this->_protocol not exist");
            }
        }
        elseif($scheme === 'udp')
        {
            $this->transport = 'udp';
        }
        
        // flag
        $flags =  $this->transport === 'udp' ? STREAM_SERVER_BIND : STREAM_SERVER_BIND | STREAM_SERVER_LISTEN;
        $this->_mainSocket = stream_socket_server($this->transport.":".$address, $errno, $errmsg, $flags, $this->_context);
        if(!$this->_mainSocket)
        {
            throw new Exception($errmsg);
        }
        
        // 尝试打开tcp的keepalive
        if(function_exists('socket_import_stream'))
        {
            $socket   = socket_import_stream($this->_mainSocket );
            @socket_set_option($socket, SOL_SOCKET, SO_KEEPALIVE, 1);
            @socket_set_option($socket, SOL_SOCKET, TCP_NODELAY, 1);
        }
        
        // 设置非阻塞
        stream_set_blocking($this->_mainSocket, 0);
        
        // 放到全局事件轮询中监听_mainSocket可读事件（客户端连接事件）
        if($this->globalEvent)
        {
            $this->globalEvent->add($this->_mainSocket, EventInterface::EV_READ, array($this, 'acceptConnection'));
        }
    }

    /**
    * 停止当前worker实例
    * @return void
    * @author dongjiang.dongj@
    */
    public function stop()
    {
        // 如果有设置进程终止回调，则执行
        if($this->onWorkerStop)
        {
            call_user_func($this->onWorkerStop, $this);
        }
        // 删除相关监听事件，关闭_mainSocket
        if (gettype($this->globalEvent) == "object") {
            $this->globalEvent->del($this->_mainSocket, EventInterface::EV_READ);
        }
        @fclose($this->_mainSocket);
    }

    /**
    * 接收一个客户端连接
    * @param resources $socket
    * @return void
    * @author dongjiang.dongj@
    */
    public function acceptConnection($socket)
    {
        // 获得客户端连接
        $new_socket = @stream_socket_accept($socket, 0);
        // 忽略, 获得new_socket id失败
        if(false === $new_socket)
        {
            return;
        }
        // 统计数据
        ConnectionInterface::$statistics['connection_count']++;
        // 初始化连接对象
        $connection = new TcpConnection($new_socket, $this->globalEvent);
        $connection->worker = $this;
        $connection->protocol = $this->_protocol;
        $connection->onMessage = $this->onMessage;
        $connection->onClose = $this->onClose;
        $connection->onError = $this->onError;
        $connection->onBufferDrain = $this->onBufferDrain;
        $connection->onBufferFull = $this->onBufferFull;
        // 记入到connections变量中
        $this->connections[(int)$new_socket] = $connection;
        
        // 如果有设置连接回调，则执行
        if($this->onConnect)
        {
            try
            {
                call_user_func($this->onConnect, $connection);
            }
            catch(Exception $e)
            {
                ConnectionInterface::$statistics['throw_exception']++;
                $this->log($e);
            }
        }
    }

    /**
    * 执行关闭流程
    * @return void
    * @author dongjiang.dongj@
    */
    public function stopAll()
    {
        $this->_status = self::STATUS_SHUTDOWN;
        // 主进程部分
        if($this->_masterPid === posix_getpid())
        {
            $this->log("phpmock[".basename($this->_startFile)."] Stopping ...");
            $worker_pid_array = $this->getAllWorkerPids();
            // 向所有子进程发送SIGINT信号，表明关闭服务
            foreach($worker_pid_array as $worker_pid)
            {
                posix_kill($worker_pid, SIGINT);
                $this->_timer->add(self::KILL_WORKER_TIMER_TIME, 'posix_kill', array($worker_pid, SIGKILL), false);
            }
            if ($this->_mainSocket) {
                @fclose($this->_mainSocket);
            }
        }
        // 子进程部分
        else
        {
            // 执行stop逻辑
            foreach($this->_workers as $worker)
            {
                $worker->stop();
            }
            if ($this->_mainSocket) {
                @fclose($this->_mainSocket);
            }
            exit(0);
        }
    }

    /**
    * 尝试设置运行当前进程的用户
    * @return void
    */
    protected function setProcessUser($user_name)
    {
        if(empty($user_name) || posix_getuid() !== 0)
        {
            return;
        }
        $user_info = posix_getpwnam($user_name);
        if($user_info['uid'] != posix_getuid() || $user_info['gid'] != posix_getgid())
        {
            if(!posix_setgid($user_info['gid']) || !posix_setuid($user_info['uid']))
            {
                $this->log( 'Notice : Can not run woker as '.$user_name." , You shuld be root\n", true);
            }
        }
    }



    /**
    * 尝试以守护进程的方式运行
    * @throws Exception
    */
    protected function daemonize()
    {
        if(!$this->daemonize)
        {
            return;
        }
        umask(0);
        $pid = pcntl_fork();
        if(-1 == $pid)
        {
            throw new Exception('fork fail !!');
        }
        elseif($pid > 0)
        {
            exit(0);
        }
        if(-1 == posix_setsid())
        {
            throw new Exception("setsid fail !!");
        }
        // fork again avoid SVR4 system regain the control of terminal
        $pid = pcntl_fork();
        if(-1 == $pid)
        {
            throw new Exception("fork fail !!");
        }
        elseif(0 !== $pid)
        {
            exit(0);
        }
    }
    
    /**
    * 获得运行当前进程的用户名
    * @return string
    * @return dongjiang.dongj@
    */
    protected function getCurrentUser()
    {
        $user_info = posix_getpwuid(posix_getuid());
        return $user_info['name'];
    }

    /**
    * 保存pid到文件中，方便运行命令时查找主进程pid
    * @warn throws Exception
    * @author dongjiang.dongj@
    */
    protected function saveMasterPid()
    {
        $this->_masterPid = posix_getpid();
        if(false === @file_put_contents($this->pidFile, $this->_masterPid))
        {
            throw new Exception('can not save pid to ' . $this->pidFile);
        }
    }

    /**
    * 执行平滑重启流程
    * @return void
    * @author dongjiang.dongj@
    */
    public function reload()
    {
        // 主进程部分
        $this->_pidsToRestart = $this->getAllWorkerPids();
        if($this->_masterPid === posix_getpid())
        {
            // 设置为平滑重启状态
            if($this->_status !== self::STATUS_RELOADING && $this->_status !== self::STATUS_SHUTDOWN)
            {
                $this->log("phpmock[".basename($this->_startFile)."] reloading");
                $this->_status = self::STATUS_RELOADING;
            }
            
            // 如果有worker设置了reloadable=false，则过滤掉
            $reloadable_pid_array = array();
            foreach($this->_pidMap as $worker_id =>$worker_pid_array)
            {
                $worker = $this->_workers[$worker_id];
                if($worker->reloadable)
                {
                    foreach($worker_pid_array as $pid)
                    {
                        $reloadable_pid_array[$pid] = $pid;
                    }
                }
            }


            // 得到所有可以重启的进程
            $this->_pidsToRestart = array_intersect($this->_pidsToRestart , $reloadable_pid_array);
            
            // 平滑重启完毕
            if(empty($this->_pidsToRestart))
            {
                if($this->_status !== self::STATUS_SHUTDOWN)
                {
                    $this->_status = self::STATUS_RUNNING;
                }
                return;
            }
            // 继续执行平滑重启流程
            $one_worker_pid = current($this->_pidsToRestart );
            // 给子进程发送平滑重启信号
            posix_kill($one_worker_pid, SIGUSR1);
            // 定时器，如果子进程在KILL_WORKER_TIMER_TIME秒后没有退出，则强行杀死
            $this->_timer->add(self::KILL_WORKER_TIMER_TIME, 'posix_kill', array($one_worker_pid, SIGKILL), false);
        }
        // 子进程部分
        else
        {
            // 如果当前worker的reloadable属性为真，则执行退出
            $worker = current($this->_workers);
            if($worker->reloadable)
            {
                $this->stopAll();
            }
        }
    } 

    /**
    * 获得所有子进程的pid
    * @return array
    * @author dongjiang.dongj
    */
    protected function getAllWorkerPids()
    {
        $pid_array = array(); 
        foreach($this->_pidMap as $worker_pid_array)
        {
            foreach($worker_pid_array as $worker_pid)
            {
                $pid_array[$worker_pid] = $worker_pid;
            }
        }
        return $pid_array;
    }

    /**
    * 将当前数据打印到屏幕，可供debug使用
    * @return void
    * @author dongjiang.dongj
    */
    public function printStatisticsToSTDOUT() {
        //TODO
        return ;
    }

    /**
    * reload所有的MOCKserver
    * @return void
    * @author dongjiang.dongj
    */
    public function reloadAll($timeout=5) {
        //TODO
        //$this->stopAll();
        //usleep(10000);
        //$this->runAll();
        $this->log("phpmock reloading!");
    }

    /**
    * 析构方法
    * @author dongjiang.dongj
    */
    public function __destruct() {
        $_pids = $this->getAllWorkerPids();
        if ($_pids != array()) {
            foreach ($_pids as $one_worker_pid) {
                posix_kill($one_worker_pid, SIGKILL);
                $this->_timer->add(self::KILL_WORKER_TIMER_TIME, 'posix_kill', array($one_worker_pid, SIGKILL), false);
            }
        }
    }

}

?>
