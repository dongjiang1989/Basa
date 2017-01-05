<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : IPlugin.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-11-28 19:13
*   Description : 
======================================================*/
declare(encoding='UTF-8');

/**
* 实现 CallFunctionFail 异常类
* @author dongjiang.dongj
*/
class CallFunctionFail extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }   
    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }   
}

/**
* 实现纯接口
*/
interface IPlugin {
}
?>
