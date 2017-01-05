<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : KfcDaemon.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-4-15 17:13
*   Description : 
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../util.php");
require_once(dirname(__FILE__)."/events/Select.php");
require_once(dirname(__FILE__)."/lib/Timer.php");


/**
* KfcDaemon Base Class.
* @uses PHP 5.3 or Higher
* @author dongjiang.dongj
*/
class KfcDaemon {

    /** 
    * 给子进程发送重启命令 KILL_WORKER_TIMER_TIME 秒后
    * 如果对应进程仍然未重启则强行杀死
    */
    const KILL_WORKER_TIMER_TIME = 1; 
   
    /**
    * worker的名称，用于在运行status命令时标记进程
    */
    public $name;
    
    /**
    * worker的名称, kfc需要加入的group
    */
    public $group;
    
    /**
    * worker的名称，kfc需要输入的sock文件
    */
    public $sock;
    
    /**
    * 设置当前worker实例的进程数
    */
    public $count = 1;

    /**
    * recvTimeOut 接受kfc client请求超时时间
    */
    public $recvTimeOut = 20000000; #ms

    /**
    * sendmsgTimeOut 返回kfc client请求超时时间
    */ 
    public $sendmsgTimeOut = 200; #ms

    /**
    * 当前work对象ID
    */  
    private $workerId = null;

    /**
    * 当客户端连接上发来数据时，如果设置了$onMessage回调，则运行
    * @var callback
    */
    public $onMessage = null;

    /**
    * private 包括worker句柄
    */ 
    private $worker;

    /**
    * 日志目录
    * 可以手动设置
    * 例如 KfcDaemon::$logFile = '/tmp/phpmock.log';
    */
    public static $logFile = '/tmp/phpmock.log';
    
    /**
    * 所有worker进程的pid
    * 格式为 [worker_id=>[pid=>pid, pid=>pid, ..], ..]
    */
    protected $_pidMap = array();
    
    /**
    * 所有worke名称(name属性)中的最大长度，用于在运行 status 命令时格式化输出
    */
    protected static $_maxWorkerNameLength = 12;

    /**
    * 设置一个Timer定时器
    */
    protected $_timer = null; 
   
    /**
    * worker构造函数
    * @param string $socket_name
    * @return void
    * @author dongjiang.dongj
    */
    public function __construct($name = "none", $group="apple", $sock="/tmp/agt.sock")
    {
        // 保存worker实例
        $this->workerId = spl_object_hash($this);
        $this->worker =& $this;
        $this->_pidMap[$this->workerId] = array();
        
        // 保存name
        $this->name = $name;
        // 保存group
        $this->group = $group;
        // 保存sock
        $this->sock = $sock;

        //Timer
        $this->_timer = new Timer();
     
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
        //$this->daemonize();
        // 初始化所有worker实例
        $this->initWorkers();
        //  初始化所有信号处理函数
        $this->installSignal();
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
        // 没有设置日志文件，则生成一个默认值
        if(empty(self::$logFile))
        {
            self::$logFile = dirname(__FILE__).'/tmp/phpmock.log';
        }
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
        if(empty($this->name))
        {
            $this->name = "none";
        }
        $worker_name_length = strlen($this->name);
        if(self::$_maxWorkerNameLength < $worker_name_length)
        {
            self::$_maxWorkerNameLength = $worker_name_length;
        }
        $_count = 0;
        // 创建子进程
        while($_count < $this->count)
        {
            $this->forkOneWorker($this->worker);
            $_count++;
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
            $this->_pidMap[$this->workerId][$pid] = $pid;
        }
        // 子进程运行
        else if(0 === $pid)
        {
            $this->_pidMap = array();
            $this->_timer->delAll();
            $ka = kfc_joingroupServer($worker->group, $worker->sock);
            if ($ka) {
                $worker->run($ka, $worker);
            } else {
                throw new Exception("forkOneWorker fail");
            }
            //kfc_leavegroup($ka);
            exit(250);
        }
        else
        {
            throw new Exception("forkOneWorker fail");
        }
    }

    protected function daemonize()
    {   
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
    * 运行worker实例
    * @author dongjiang.dongj@
    */
    public function run($fp, $worker)
    {
        while(true) {
            try {
                $msg = kfc_recvmsgServer($fp, KFC_SYNC, $worker->recvTimeOut);
                if ($msg === false) {
                    logging::error("kfc_recvmsgServer status error! status:", $msg);
                    continue;
                }

                $buf = null;

                if ( $worker->onMessage )
                {
                    call_user_func_array($worker->onMessage, array($msg, &$buf));
                }

                $res = kfc_sendmsgServer($fp, $buf, KFC_SYNC, $worker->sendmsgTimeOut);
                if ($res === false) {
                    logging::error("kfc_sendmsgServer status error! status:", $msg);
                    continue;
                }

            } catch (exception $e) {
                logging::error(" Send and recv exception!! msg: ". $e->getMessage());
                continue;
            }   
        }
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
        file_put_contents(self::$logFile, date('Y-m-d H:i:s') . " " . $msg, FILE_APPEND | LOCK_EX);
    }

    /**
    * 初始化所有的worker实例，主要工作为获得格式化所需数据及监听端口
    * @return void
    * @author dongjiang.dongj@
    */
    protected function initWorkers()
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
    }

    /**
    * 停止当前worker实例
    * @return void
    * @author dongjiang.dongj@
    */
    public function stop()
    {
        
    }

    /**
    * 执行关闭流程
    * @return void
    * @author dongjiang.dongj@
    */
    public function stopAll()
    {
        // 主进程部分
        $this->_pidsToRestart = $this->getAllWorkerPids();
        foreach ($this->_pidsToRestart as $one_worker_pid) {
            // 给子进程发送平滑重启信号
            posix_kill($one_worker_pid, SIGKILL);
            // 定时器，如果子进程在KILL_WORKER_TIMER_TIME秒后没有退出，则强行杀死
            $this->_timer->add(self::KILL_WORKER_TIMER_TIME, 'posix_kill', array($one_worker_pid, SIGKILL), false);
        }
        $this->_pidMap[$this->workerId] = array();
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
        foreach ($this->_pidsToRestart as $one_worker_pid) {
            // 给子进程发送平滑重启信号
            posix_kill($one_worker_pid, SIGKILL);
            // 定时器，如果子进程在KILL_WORKER_TIMER_TIME秒后没有退出，则强行杀死
            $this->_timer->add(self::KILL_WORKER_TIMER_TIME, 'posix_kill', array($one_worker_pid, SIGKILL), false);
        }
        $this->_pidMap[$this->workerId] = array();
        $this->forkWorkers();
    } 

    /**
    * 获得所有子进程的pid
    * @return array
    * @author dongjiang.dongj
    */
    public function getAllWorkerPids()
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
        $this->reload();
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
            $this->_pidMap[$this->workerId] = array();
        }
    }

}

?>
