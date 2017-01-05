<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : file.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-10-24 19:13
*   Description : 
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/Eobject.php");

/** 实现initFail异常 */
class InitFail extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }
    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ .": [ret:{$this->code}]: {$this->message}\n";
    }
}

/**
* 虚接口. 需要重载.
* @author dongjiang.dongj
*/
class AbstractInterface extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }
    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ .": [ret:{$this->code}]: {$this->message}\n";
    }
}

if (!class_exists('file')) {
    /**
    * 文件处理类. 
    *   实现基本的文件操作. 包括：基本的 grep、cat、add、find、echo 等基本文件操作.
    * @author dongjiang.dongj
    */
    class file extends Eobject {
        private $host;
        private $filename;
        private $auto_bak;
        private $init_from;
        private $is_touch;
        private $basename;
        private $pathname;
        private $fullname;
        private $_name;
        private $_backid;
        private $_cpath;
        private $_from;
        
        /**
        * 文件类构造方法.
        * @param $filename string. 输入需要加载的文件名称.
        *      可以是相对路径，也可以是绝对路径.
        * @param $host. 为文件所在机器的ip or hostname
        * @param $init_from string. default文件目录，可用于将环境中部分文件，替换成的测试版本
        * @param $auto_bak bool. default为False. 文件是否需要备份和还原.
        * @param $is_touch bool. default为True. 如果文件不存在，是否touch出一个空文件
        * @return void
        * @author dongjiang.dongj
        * @warn 可能会抛出InitFail异常
        */
        public function __construct($filename, $host="127.0.0.1", $init_from="", $auto_bak=False, $is_touch = True){
            parent::__construct($host);
            $this->host = $host;
            $this->filename = $filename;
            $this->init_from = $init_from;
            $this->auto_bak = $auto_bak;
            $this->is_touch = $is_touch;

            $this->_name = __CLASS__;
            $this->fullname = (realpath(dirname($filename))=="") ? 
                              ( (realpath(dirname(getcwd()."/".$filename))=="") ?
                                $filename :
                                realpath(dirname(getcwd()."/".$filename))."/".basename($filename)
                              ) :
                              realpath(dirname($filename))."/".basename($filename);
            $this->pathname = dirname($this->fullname);
            $this->basename = basename($this->fullname);
            try {
                $this->init($this->init_from);
            }
            catch (Exception $e){
                throw new InitFail("file init default environment Fail! \n ".$e->__toString(), RETTYPE::ERR);
            }
        }
    
        /**
        * 环境初始化方法
        * @author dongjiang.dongj
        */
        public function init($from) {
            $this->_from = $from;
            $_ret = $this->run("test -d '".$this->pathname."'", $_exc = False);
            if ( $_ret[0] != 0 ) {
                logging::warn("$this->host:$this->pathname is not exist, mkdir for it");
                $this->_cpath = $this->pathname;
                $_ret = $this->run("test -d ".dirname($this->_cpath), $_exc=False);
                while ($_ret[0] != 0) {
                    $this->_cpath = dirname($this->_cpath);
                    $_ret = $this->run("test -d ".dirname($this->_cpath), $_exc=False);
                }
                logging::debug("$this->host:$this->_name : cpath = $this->_cpath");

                $this->run("mkdir -p ".$this->pathname);
            }
            else {
                $this->_cpath = "";
            }

            $this->_create_backup();
        }

        /**
        * 文件自动备份方法
        * @author dongjiang.dongj
        */
        private function _create_backup() {
            $ret = $this->run("test -f '".$this->fullname."'", $_exc = False);
            if ( $ret[0] == 0) {
                logging::debug($this->fullname." is exist");
                if ($this->auto_bak){
                    logging::debug($this->_name.": backup ".$this->fullname);
                    $bakfix = $this->pathname."/.".$this->basename.".".$this->_name;
                    $this->_backid = $bakfix.".".mt_rand(1024,65535);
                    if ($this->_from === "") {
                        $this->run("rm ".$bakfix.".* &> /dev/null; cp ".$this->fullname." ".$this->_backid);
                    }
                    else if ($this->_from === null) {
                        logging::warn(__CLASS__." create empty file '".$this->fullname."'");
                        $this->run("rm ".$bakfix.".* &> /dev/null; mv ".$this->fullname." ".$this->_backid."; touch ".$this->fullname);
                    }
                    else {
                        logging::info("init ".$this->fullname." from ".$this->_from);
                        $this->run("rm ".$bakfix.".* &> /dev/null;mv ".$this->fullname." ".$this->_backid);
                        $this->rcp( $this->_from, $this->host.":".$this->pathname);
                    }
                }
                else {
                    $this->_backid = null;
                    if ($this->_from) {
                        $this->rcp( $this->_from, $this->host.":".$this->pathname);
                    }
                }
                
            }
            else {
                $this->_backid = null;
                if (!$this->_from) {
                    logging::warn($this->fullname." is not exist");
                    if ($this->is_touch) {
                        $this->run("touch ".$this->fullname);
                    }
                }
                else {
                    $this->rcp( $this->_from, $this->host.":".$this->pathname);
                }
            }
        }

        /**
        * 文件自动恢复
        * @author dongjiang.dongj
        */
        function roll_back() {
            try{
                if ($this->_cpath)
                    $this->run("rm -rf ".$this->_cpath);
                else {
                    if ( $this->_backid == null )
                        $this->run("rm -rf".$this->fullname);
                    else
                        $this->run("mv ".$this->_backid." ".$this->fullname);
                }
                logging::debug($this->_name.": auto rollback ".$this->fullname." succeed");
            }
            catch( ExecuteFail $e) {
                logging::debug($this->_name.": auto rollback ".$this->fullname." fail, ".$e->__toString());
            }
            catch( Exception $e) {
                logging::debug($this->_name.": auto rollback ".$this->fullname." fail, ".$e->__toString());
            }
        }

        /**
        * 文件重置方法.
        *   当文件被使用更改后，内部通过reload更新
        * @return bool
        * @author dongjiang.dongj
        */
        function reset() {
            if ($this->auto_bak && $this->_backid) {
                try {
                    $this->load($this->_backid);
                }
                catch (ExecuteFail $e) {
                    logging::warn($this->basename()."reset fail, maybe reset by some other confs before?\n".$e->__toString());
                }
            }
            else {
                logging::warn($this->fullname." not backed, reset to empty");
                $this->clear();
            }
            return True;
        }

        /**
        * host，获得文件所在host
        * @return $this->host.
        * @author dongjiang.dongj
        */
        function host() {
            return $this->host;
        }

        /**
        * 获得文件全路径realpath
        * @return $this->fullname
        * @author dongjiang.dongj
        */
        function fullname() {
            return $this->fullname;
        }

        /**
        * 获得文件所在的dirname路径
        * @return dirname($filename)
        * @author dongjiang.dongj
        */
        function pathname() {
            return $this->pathname;
        }
        
        /**
        * 获得文件名.
        * @author dongjiang.dongj
        */
        function basename() {
            return $this->basename;
        }

        /**
        * 获得文件大小.
        *    使用 wc 与 cut 工具
        * @return byte counts.
        * @author dongjiang.dongj
        */
        function size() {
            $output = $this->run("wc -c ".$this->fullname."|cut -d' ' -f1", False);
            if ($output[0] == 0 && $output[1] != Array()) {
                return (int)$output[1][0];
            }
            else {
                return 0;
            }
        }

        /**
        * 获得文件md5sum 
        *   使用 md5sum 和 cut 工具
        * @return md5 string. 32位十六进制数
        * @author dongjiang.dongj
        */
        function md5sum() {
            $output = $this->run("md5sum ".$this->fullname."|cut -d' ' -f1", False);
            if ($output[0] == 0 && $output[1] != Array()) {
                return $output[1][0];
            }
            else {
                return null;
            }
        }
        
        /**
        * 清空文件
        * @return bool
        * @author dongjiang.dongj
        */
        function clear() {
            return $this->run( "test -f ".$this->fullname." && > ".$this->fullname." || true", false);
        }

        /**
        * 获得文件行数
        * @return int
        * @author dongjiang.dongj
        */
        function lines() {
            $res = $this->sed("-n","$=", False);
            if ($res && !($res == Array()) && !($res == Array(''))) {
                return (int)$res[0];
            }
            else {
                return 0;
            }
        }

        /**
        * rm 文件
        * @return array($status, array($output))
        * @author dongjiang.dongj
        */
        function remove() {
            return $this->run("rm -rf ".$this->fullname);
        }

        /**
        * abstract function. subclass 必须实现它
        * @warn 抛出AbstractInterface异常
        * @author dongjiang.dongj
        */
        function get() {
           throw new AbstractInterface(__CLASS__." should overload this API", RETTYPE::ERR);
        }
        
        /**
        * abstract function. subclass 必须实现它
        * @warn 抛出AbstractInterface异常
        * @author dongjiang.dongj
        */
        function set() {
            throw new AbstractInterface(__CLASS__." should overload this API", RETTYPE::ERR);
        }

        /**
        * 实现向文件最后中加一行数据
        * @return array($status, array($output))
        * @author dongjiang.dongj
        */
        function add($data) {
            return $this->run("echo ".$data." >> ".$this->fullname);
        }

        /**
        * abstract function. subclass 必须实现它
        * @warn 抛出AbstractInterface异常
        * @author dongjiang.dongj
        */
        function delete() {
            throw new AbstractInterface(__CLASS__." should overload this API", RETTYPE::ERR);
        }

        /**
        * 替换文件
        * @param $file . 新文件
        * @return array($status, array($output))
        * @author dongjiang.dongj
        */
        function load($file) {
            return $this->run("cat ".$file." > ".$this->fullname);
        }

        /**
        * 使用新数据重新写到文件
        * @return array($status, array($output))
        * @author dongjiang.dongj
        */
        function feed($data) {
            return $this->run("echo \"".$data."\" > ".$this->fullname);
        }

        /**
        * 实现grep方法. 使用到系统工具grep
        * @param $opts. grep 自带参数
        * @param $value. grep具体内容
        * @param $_exc. 继承参数，是否异常
        * @return Array($output)
        * @author dongjiang.dongj
        */
        function grep($opts='', $value='', $_exc=true) {
            $output = $this->run("grep ".$opts." ".$value." ".$this->fullname, $_exc);
            return $output[1];
        }
        
        /**
        * 实现 cat 方法. 调用系统cat命令
        * @param $opts. cat 自带参数
        * @param $_exc. 继承参数，是否异常
        * @return Array($output)
        * @author dongjiang.dongj
        */
        function cat($opts='', $_exc=True) {
            $output = $this->run("cat ".$opts." ".$this->fullname, $_exc);
            return $output[1];
        }

        /**
        * 实现 sed 方法. 调用系统sed命令
        * @param $opts. sed 自带参数
        * @param $value. sed 具体内容
        * @param $_exc. 继承参数，是否异常
        * @return Array($output)
        * @author dongjiang.dongj
        */
        function sed($opts='', $value='', $_exc=True) {
            $output = $this->run("sed ".$opts." ".$value." ".$this->fullname, $_exc);
            return $output[1];
        }

        /**
        * 实现 tail 方法. 调用系统tail命令
        * @param $opts. tail 自带参数
        * @param $_exc. 继承参数，是否异常
        * @return Array($output)
        * @author dongjiang.dongj
        */
        function tail($opts='', $_exc=True) {
            $output = $this->run("tail ".$opts." ".$this->fullname, $_exc);
            return $output[1];
        }

        /**
        * 实现 head 方法. 调用系统head命令
        * @param $opts. head 自带参数
        * @param $_exc. 继承参数，是否异常
        * @return Array($output)
        * @author dongjiang.dongj
        */
        function head($opts='', $_exc=True) {
            $output = $this->run("head ".$opts." ".$this->fullname, $_exc);
            return $output[1];
        }

        /**
        * 另存为 方法.
        * @param $filename. 如果为空，存到当前执行路径
        * @param $_exc. 继承参数，是否异常
        * @return array( $status, array($output) )
        * @author dongjiang.dongj
        */
        function saveto($filename='', $_exc=True) {
            if ($filename) {
                return $this->rcp( $this->host.":".$this->fullname, $filename, $_exc);
            }
            else {
                #return $this->rcp( $this->host.":".$this->fullname, dirname(__FILE__), $_exc);
                return $this->rcp( $this->host.":".$this->fullname, getcwd(), $_exc);
            }
        }

        /**
        * 析构方法
        *   自动rollback file
        * @author dongjiang.dongj
        */
        public function __destruct(){
            if ($this->auto_bak) {
                $this->roll_back();
            }
            parent::__destruct();
        }
        
    }
}
else {
    throw new Exception("class file has been defined!");
}
?>
