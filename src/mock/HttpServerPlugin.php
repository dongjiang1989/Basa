<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : HttpServerPlugin.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2015-04-21 19:13
*   Description   : Http类型mock
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/MockIPlugin.php");
require_once(dirname(__FILE__)."/../com/util.php");
require_once(dirname(__FILE__)."/../com/Eobject.php");
require_once(dirname(__FILE__)."/../com/Daemon/Daemon.php");
require_once(dirname(__FILE__)."/../machine.php");

/**
* HttpServer MOCK
* @author dongjiang.dongj
*/
class HttpServerPlugin extends Eobject implements MockIPlugin {

    const MODE = "HttpMockServer";
    const CLASSNAME = __CLASS__;

    private $_mockserver = null;
    private $_domain = null;
    public $_mockname = null;
    private $_protocol = null;
    private $_port = null;
    private $_count = null;
    /**
    * 析构方法
    * @param domain: mock的地址
    * @param port: mock的端口
    * @author dongjiang.dongj 
    */
    public function __construct($name, $count=1, $domain="127.0.0.1", $port="4321"){
        parent::__construct($host="127.0.0.1");
        //TODO

        // set name
        $this->_mockname = $name;

        // set domain
        $this->_protocol = "http";

        // set count
        $this->_count = $count;

        if (!is_local($domain)) {
            $this->_domain = Machine::getIp();
        } else {
            $this->_domain = $domain;
        }

        // set port
        if ($this->IsPortUsed($port) == true) {
            $_port = rand(40000, 65535);
            if ($this->IsPortUsed($_port) == false) {
                $this->_port = $_port;
            }
        } else {
            $this->_port = $port;
        }

        try {
            $this->_mockserver = new Daemon($this->_protocol."://".$this->_domain.":".$this->_port);
            $this->_mockserver->name = $this->_mockname;
            $this->_mockserver->name = $this->_count;
        } catch (exception $e) {
            logging::error("New HttpMockServer error! error:", $e->getMessage());
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
        } catch (exception $e) {
            logging::error("Mockserver Start Exception!!!", $e->getMessage());
            return false;
        }
        if ($this->IsPortUsed($this->_port)) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * public 方法，停止mockserver
    * @return bool
    * @author dongjiang.dongj
    */
    public function stop() {
        try {
            $this->_mockserver->stopAll();
            usleep(1000);
        } catch (exception $e) {
            logging::error("Mockserver Stop Exception!!!", $e->getMessage());
            return false;
        }
        if ($this->IsPortUsed($this->_port) == false) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * private 方法，获得指定section范围
    * @param _get_section: getSections时返回行号区间包含子section(范围更大)
    * @param _get_key    : 用于有多个[GLOBAL]的情况，返回包含需要修改的key的[GLOBAL]的范围
    * @author dongjiang.dongj
    */
    private function IsPortUsed($port="") {
        if ($port == "") {
            $retArr = $this->run("netstat -ano | grep LISTEN | grep ".$this->_port);
            if ($retArr[0] == "1" && count($retArr[1]) == 0 && $retArr[1] === array()) {
                return False;
            } else {
                return True;
            }
        } else {
            $retArr = $this->run("netstat -ano | grep LISTEN | grep ".$port);
            if ($retArr[0] == "1" && count($retArr[1]) == 0 && $retArr[1] === array()) {
                return False;
            } else {
                return True;
            }
        }
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
    *  获得server domain
    * @return string domain
    * @author dongjiang.dongj
    */
    public function getDomain() {
        return $this->_domain;
    }

    /**
    *  获得server port
    * @return int port
    * @author dongjiang.dongj
    */
    public function getPort() {
        return $this->_port;
    }

    /**
    * 获得mock url
    * @return string
    * @author dongjiang.dongj
    */
    public function getUrl() {
        return $this->_protocol."://".$this->_domain.":".$this->_port;
    }

    /**
    * callback function
    * @author dongjiang.dongj
    */
    public function callback($func) {
        $this->_mockserver->onMessage = $func;
    }

    /**
    * 析构方法
    * @author dongjiang.dongj
    */
    public function __destruct() {
        if ($this->_mockserver) {
            $this->_mockserver->__destruct();
        }
        //TODO
        parent::__destruct();       
    }
}

?>
