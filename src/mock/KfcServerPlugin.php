<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : KfcServerPlugin.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2015-04-26 18:32
*   Description   : Kfc类型mock
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/MockIPlugin.php");
require_once(dirname(__FILE__)."/../com/util.php");
require_once(dirname(__FILE__)."/../com/Eobject.php");
require_once(dirname(__FILE__)."/../com/Daemon/kfcDaemon.php");
require_once(dirname(__FILE__)."/../machine.php");

/**
* KfcServer MOCK
* @author dongjiang.dongj
*/
class KfcServerPlugin extends Eobject implements MockIPlugin {

    const MODE = "KfcMockServer";
    const CLASSNAME = __CLASS__;

    private $_mockserver = null;
    private $_domain = null;
    public $_mockname = null;
    private $_group = null;
    private $_sock = null;
    private $_process_count = 0;
    /**
    * 析构方法
    * @param domain: mock的地址
    * @param port: mock的端口
    * @author dongjiang.dongj 
    */
    public function __construct($name, $count=1, $group="apple", $sock="/tmp/agt.sock"){
        parent::__construct($host="127.0.0.1");
        //TODO

        // set name
        $this->_mockname = $name;

        // set group
        $this->_group = $group;

        // set sock
        $this->_sock = $sock;

        // set process count
        $this->_process_count = $count;

        try {
            $this->_mockserver = new KfcDaemon($this->_mockname, $this->_group, $this->_sock);
            $this->_mockserver->name = $this->_mockname;
            $this->_mockserver->count = $this->_process_count;
        } catch (exception $e) {
            logging::error("New KfcMockServer error! error:", $e->getMessage());
            exit(255);
        }
    }
    
    /**
    * 方法重载使用到的系统回调方法
    * @author dongjiang.dongj 
    */
    public function __call($f, $p) {
        if (method_exists($this, $f.sizeof($p))) {
            return call_user_func_array(array($this, $f.sizeof($p)), $p);
        } else {
            throw new CallFunctionFail("Tried to call unknown method".get_class($this).'::'.$f, RETTYPE::ERR);
        }
    }

    /**
    * public 方法，启动mockserver
    * @return bool
    * @author dongjiang.dongj
    */
    public function start() {
        try {
            $this->_mockserver->runAll();
            sleep(1);
            $_count = 20;
            while ($this->is_started() == false &&  $_count > 0) {
                $_count = $_count - 1;
                if ($_count == 0) {
                    return false;
                }
            }
            return true;            
        } catch (exception $e) {
            logging::error("Mockserver Start Exception!!!", $e->getMessage());
            return false;
        }
        return true;
    }

    /**
    * private 方法，判断是否启动
    * @return bool
    * @author dongjiang.dongj
    */
    private function is_started() {
        $pids = $this->_mockserver->getAllWorkerPids();
        foreach($pids as $pid) {
            $_match = "/".$this->_group."_s_(.*)_".$pid."/";
            $_ret = $this->run("cat /dev/kuafu_alive");
            if ( gettype($_ret) == "array" && count($_ret) == 2 && $_ret[0] == 0 ) {
                if ( preg_grep($_match, array_values($_ret[1])) == array() ) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
    * public 方法，停止mockserver
    * @return bool
    * @author dongjiang.dongj
    */
    public function stop() {
        try {
            if ( defined('_BLOCK') ) {
                logging::pause("MOCK has been PAUSE!!!, Now you can Debug!");
            }
            $this->_mockserver->stopAll();
            $_count = 20;
            while ($this->is_stoped() == false &&  $_count > 0) {
                $_count = $_count - 1;
                if ($_count == 0) {
                    return false;
                }
            }
            return true;            
        } catch (exception $e) {
            logging::error("Mockserver Stop Exception!!!", $e->getMessage());
            return false;
        }
        return true;
    }

    /**
    * private 方法, 判断是否停止
    * @return bool
    * @author dongjiang.dongj
    */
    private function is_stoped() {
        $pids = $this->_mockserver->getAllWorkerPids();
        foreach($pids as $pid) {
            $_match = "/".$this->_group."_s_(.*)_".$pid."/";
            $_ret = $this->run("cat /dev/kuafu_alive");
            if ( gettype($_ret) == "array" && count($_ret) == 2 && $_ret[0] == 0 ) {
                if ( preg_grep($_match, array_values($_ret[1])) != array() ) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
    * set MOCKname
    * @author dongjiang.dongj
    */
    public function setName($name) {
        $this->_mockname = $name;
        $this->_mockserver->name = $this->_mockname;
    }

    /**
    *  获得server group
    * @return string group
    * @author dongjiang.dongj
    */
    public function getGroup() {
        return $this->_group;
    }

    /**
    *  获得server sock
    * @return string spck
    * @author dongjiang.dongj
    */
    public function getSock() {
        return $this->_sock;
    }

    /**
    * reload 请求
    * @return bool
    * @author dongjiang.dongj
    */
    public function reload() {
        try {
            $this->_mockserver->reloadAll();
            sleep(1);
        } catch (exception $e) {
            logging::error("Mockserver reload Exception!!!", $e->getMessage());
            return false;
        }
        return true;
    }

    /**
    * callback function
    * @author dongjiang.dongj
    */
    public function callback($func) {
        $this->_mockserver->onMessage = $func;
    }

    public function __destruct(){
        if ($this->_mockserver) {
            $this->_mockserver->__destruct();
        }
        parent::__destruct();
    }
}

?>
