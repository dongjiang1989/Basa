<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : baseFactory.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-11-29 11:21
*   Description : 
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/Object.php");
/**
* 实现 RegisterFail 异常类
* @author dongjiang.dongj
*/
class RegisterFail extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }
    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }
}

/**
* 实现工厂模型基础抽象类
* @author dongjiang.dongj
*/
abstract class BaseFactory extends Object {
    public function __construct(){
        parent::__construct();
        //TODO
    }

    abstract public static function initialize($config);

    abstract public static function RegisterPlugin($classname, $params="");

    abstract public function getConfigInstance();

    abstract public function getInstanceHandle($mode, $params="");

    abstract public static function RegisterAllPlugins();

    abstract public static function getPluginMode();

    abstract public function unRegisterInstance($filename);

    abstract public static function check($classname);

    abstract public function unRegisterAllInstance();

    abstract public function getInstanceHandles();

    public function __destruct(){
        //TODO
        parent::__destruct();
    }
}

?>
