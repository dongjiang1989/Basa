<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/com/util.php");
require_once(dirname(__FILE__)."/com/file.php");
class Process extends Eobject {

    private $BINPATH;
    const DEFAULT_HOME = "/home/a/";
    private $CONFPATH;
    private $LOGSPATH;

    private $_name;
    private $_auto_del;
    private $_init_from;
    private $_start_time;
    private $_home;
    
    private $user_coreprttern;

    /**
    *   process 启停、操作等相关方法
    * @param $name 设置模块启动的模块名
    * @param $home 模块启停目录
    * @param $host 模块所在机器
    * @param $init_from 如果模块使用init本地备份环境
    * @param $auto_del 是否情况环境
    * @author dongjiang.dongj
    */
    public function __construct($name , $home="", $host="127.0.0.1", $init_from="", $auto_del=false) {
        parent::__construct($host);
        updated_data_TS();
   
        $this->_name = $name != false ? $name : strtolower(__CLASS__);
        $this->BINPATH = self::DEFAULT_HOME."search/bin";
        $this->CONFPATH = self::DEFAULT_HOME."search/conf";
        $this->LOGSPATH = self::DEFAULT_HOME."search/logs";

        $this->user_coreprttern = rtrim(trim($this->LOGSPATH), '/');

        $this->_home = ($home != false) ? $home : self::DEFAULT_HOME;
        $this->_start_time = null;

        $this->_init_from = $init_from;
        $this->_auto_del = $auto_del;

        try { // mkdir DEFAULT_HOME
            $this->run("mkdir -p \"".self::DEFAULT_HOME."\"");
        } catch (Exception $e) {}

        try {
            if ($init_from) {
                $this->rcp($init_from, $this->gethost().":".$this->_home);
            }
        } catch (ExecuteFail $e) {
            throw new InitFail($this->_name." init fail, err:\n".$e->message(), RETTYPE::ERR);
        }
    }

    /**
    *  设置Core Dump的环境变量、dump路径以及core形式
    * @param $path 设置core dump的路径
    * @return bool
    * @author dongjiang.dongj
    */
    public function setCoreDumpControl($path="") {
        list($s, $o) = $this->run("ulimit -c");
        $ret = 0; 
        if($s == 0 && count($o) == 1 && $o[0] == "unlimited") {
            $ret = $ret | $s;
        } else {
            $profile = new file($filename="/etc/profile", $host=$this->gethost(), $init_from="", $auto_bak=False);
            $_ret = $profile->sed("-n", "/ulimit.*.-c/p");
            if (count($ret) > 0) {
                $profile->run("sudo sed -i /ulimit.*.-c/d /etc/profile");
                $_ret = $profile->run("sudo sh -c \"echo 'ulimit -S -c unlimited > /dev/null 2>&1' >> /etc/profile\"");
                $ret = $_ret[0] | $ret;
            } else {
                $_ret = $profile->run("sudo sh -c \"echo 'ulimit -S -c unlimited > /dev/null 2>&1' >> /etc/profile\"");
                $ret = $_ret[0] | $ret;
            }
        }
        $this->run("ulimit -S -c unlimited > /dev/null 2>&1");
        $this->run("source /etc/profile");

        list($s, $o) = $this->run("sudo cat /proc/sys/kernel/core_uses_pid");

        if ($s == 0 && count($o) == 1 && $o[0] == 1) {
            $ret = $ret | 0;
        } else {
            list($s, $o) = $this->run("sudo echo '1' > /proc/sys/kernel/core_uses_pid");
            $ret = $ret | $s;
        }

        list($s, $o) = $this->run("sudo cat /proc/sys/kernel/core_pattern");

        if ($path == "") {
            if ($s == 0 && count($o) == 1 && $o[0] == rtrim(trim($this->LOGSPATH), '/')."/core.%e_%t") {
                $ret = $ret | 0;
            } else {
                logging::info("set core dump path : ", rtrim(trim($this->LOGSPATH), '/')."/");
                list($s, $o) = $this->run("mkdir -p \"".rtrim(trim($this->LOGSPATH), '/')."\" && sudo /sbin/sysctl -w kernel.core_pattern=".rtrim(trim($this->LOGSPATH), '/')."/core.%e_%t");
                $ret = $ret | $s;
            }
            
        } else {
            $this->user_coreprttern = rtrim(trim($path), '/');
            if ($s == 0 && count($o) == 1 && $o[0] == rtrim(trim($path), '/')."/core.%e_%t") {
                $ret = $ret | 0;
            } else {
                logging::info("set core dump path : ", rtrim(trim($path), '/')."/");
                list($s, $o) = $this->run("mkdir -p \"".rtrim(trim($path), '/')."\" && sudo /sbin/sysctl -w kernel.core_pattern=".rtrim(trim($path), '/')."/core.%e_%t");
                $ret = $ret | $s;
            }
        }
 
        $this->run("source  ~/.bash_profile");
        return ($ret == 0);
    }

    /**
    *  清除coredump 环境的以及存在各种core文件
    * @return bool
    * @author dongjiang.dongj
    */
    public function cleanCore() {
        list($s, $o) = $this->run("sudo rm -rf ".$this->user_coreprttern."/core*");
        return ($s == 0);
    }

    /**
    *  check 是否有core文件dump
    * @return bool
    * @author dongjiang.dongj
    */
    public function isCoreDumped($pid="") {
        if($pid=="") {
            list($s, $o) = $this->run("ls ".$this->user_coreprttern."/core.*_*");
            return($s == 0 && count($o)>0);
        } else {
            list($s, $o) = $this->run("ls ".$this->user_coreprttern."/core.*_*.".$pid);
            return($s == 0 && count($o)>0);
        }
    }

    /**
    *  通用启动命令
    * @param $_exc bool. 是否抛出异常
    * @param $cmd string. 启动命令
    * @return bool
    * @author dongjiang.dongj
    */
    public function start($cmd, $_exc = False) {
        return $this->run($cmd, $_exc);
    }

    /**
    *  通用停止命令
    * @param $_exc bool. 是否抛出异常
    * @param $cmd string. 启动命令
    * @return bool
    * @author dongjiang.dongj
    */
    public function stop($cmd, $_exc = False) {
        return $this->run($cmd, $_exc);
    }

    /**
    *  回调用户自定义function，实现check 模块启动
    * @param $user_function 回调用户function
    * @param $timeout 启动是否超时
    * @return $user_function的return
    * @author dongjiang.dongj
    */
    public function checkStart($user_function, $timeout=10) {
        $ret = call_user_func($user_function);
        return $ret;
    }
    
    /**
    *  回调用户自定义function，实现check 模块停止
    * @param $user_function 回调用户function
    * @return $user_function的return
    * @author dongjiang.dongj
    */
    public function checkStop($user_function) {
        $ret = call_user_func($user_function);
        return $ret;
    }

    /**
    *   模块所在目录（realpath）
    * @return string. 模块所在目录（realpath）
    * @author dongjiang.dongj
    */
    public function gethome() {
        return $this->_home;
    }

    /**
    * 回模块所在机器 IP
    * @return string.
    * @author dongjiang.dongj
    */
    public function getIp() {
        $ret = $this->run("hostname -i");
        if (count($ret) == 2 && $ret[0] == "" && count($ret[1]) == 1 && 
            preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ret[1][0])) {
            return $ret[1][0];
        } else {
            $ret = $this->run("php -r \"echo gethostbyname(gethostname());\"");
            if (count($ret) == 2 && $ret[0] == "" && count($ret[1]) == 1 && 
                preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ret[1][0])) {
                return $ret[1][0];
            }
            return false;
        }
    }

    /**
    *  模块使用valgrind方式启动
    * @param 魔法传参
    * @return bool
    * @author dongjiang.dongj
    */
    public function valgrindStart() {
        return True;
    }

    /**
    *  模块valgrind方式stop
    * @param 魔法传参
    * @return bool
    * @author dongjiang.dongj
    */
    public function valgrindStop() {
        return True;
    }

    /**
    *  模块启动进程的pid，ps结果
    * @return string
    * @author dongjiang.dongj
    */
    public function getPid($string="") {
        $status = 1;
        $output = array();
        if ($string != "") {
            list($status, $output) = $this->run("pgrep \"".$string."\"");
        } else {
            list($status, $output) = $this->run("pgrep \"".$this->_name."\"");
        }
        if ($status == 0) {
            return $output;
        } else {
            return array();
        }
    }

    private function _checkProcess($string="") {
        $status = 1;
        $output = array();
        if ($string == "") {
            list($status, $output) = $this->run("ps -C \"".$this->_name."\" &> /dev/null");
        } else {
            list($status, $output) = $this->run("ps -C \"".$string."\" &> /dev/null");
        }
        return $status;
    }

    /**
    *  模块当前使用的物理内存(kbyte)，ps结果
    * @return int
    * @author dongjiang.dongj
    */
    public function getMem($string="") {
        if ($this->_checkProcess($string) == 0) {
            $output = array();
            if ($string == "") {
                list($status, $output) = $this->run("ps -o rss -C \"".$this->_name."\"|tail -1");
            } else {
                list($status, $output) = $this->run("ps -o rss -C \"".$string."\"|tail -1");
            }
            return $output[0];
        } else {
            logging::error( "Process is not exist!");
            return false;
        }
    }

    /**
    *  判断当前模块是否还在
    * @return bool
    * @author dongjiang.dongj
    */
    public function is_Alive($string="") {
        return ($this->_checkProcess($string) == 0);
    }    

    /**
    * 获得模块当前启动的线程数
    * @return int
    * @author dongjiang.dongj
    */
    public function getThreads($string="") {
        if ($this->_checkProcess($string) == 0) {
            $status = 1;
            $output = array();
            if ($string == "") {
                list($status, $output) = $this->run("echo $(($(ps -m -C \"".$this->_name."\" | wc -l) - 3))");
            } else {
                list($status, $output) = $this->run("echo $(($(ps -m -C \"".$string."\" | wc -l) - 3))");
            }
            if ((int)$output[0] > 0) {
                return (int)$output[0];
            } else {
                return 0;
            }
        } else {
            logging::error( "process is not exist!");
            return 0;
        }
        
    }

    /**
    *  传递参数给reload，用法
    * @param 魔法传参
    * @return array($status, $output)
    * @author dongjiang.dongj
    */
    public function reload($cmd, $_exc = False) {
        return $this->run($cmd, $_exc);
    }

    /**
    *  获得进程所占当前使用的虚拟内存(kbyte)，ps结果
    * @return int
    * @author dongjiang.dongj
    */
    public function getVmem($string="") {
        if ($this->_checkProcess($string) == 0) {
            $output = array();
            if ($string == "") {
                list($status, $output) = $this->run("ps -o vsz -C \"".$this->_name."\"|tail -1");
            } else {
                list($status, $output) = $this->run("ps -o vsz -C \"".$string."\"|tail -1");
            }
            return $output[0];
        } else {
            logging::error( "Process is not exist!");
            return false;
        }
    }

    /**
    *  获取进程名
    * @return string
    * @author dongjiang.dongj
    */
    public function getProc() {
        return $this->_name;
    }

    /**
    *  获得子进程列表方法。 
    * @param index 获得cpid的深度数据
    *   如果$index == null，数据为所有子进程列表；如果index不为null，获得对应进程下，子进程列表
    * @return array
    * @author dongjiang.dongj
    */
    public function getCpid($index=null, $string="")  {
        $pid = $index;
        if ($pid == null) {
            $ret = $this->getPid($string);
            $pid = $ret[0];
        }
        list($status, $output) = $this->run("pstree -p \"".$pid."\" 2>/dev/null|egrep -o '[\}]\([0-9]+'|cut -d '(' -f2|grep -v \"".$pid."\"");
        return $output[0];
    }

    /** 析构方法 */
    public function __destruct(){
        parent::__destruct();
    }
}

?>
