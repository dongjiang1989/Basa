<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : dbserver.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-12-12 19:13
*   Description   : 通用Json解析类
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../../com/mysqlbase.php");
/**
* Table 处理方法
*      支持块的 分级、查找、替换和缩进等
* @author dongjiang.dongj
*/
class DBServer extends mysqlBase{

    /**
    * DataBase构造方法
    *   实现 DataBase 的基本操作
    * @author dongjiang.dongj 
    */
    public function __construct(){
        parent::__construct();
        //TODO
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
    * 获得DBserver中的DB数据
    * @return array.
    * @author dongjiang.dongj
    */
    public function getDBs(){
    }

    /**
    *  判断DBserver中是否有DB
    * @param $table string.
    * @return bool.
    * @author dongjiang.dongj
    */
    public function has_DB($table) {
        return True;
    }

    /** 
    *  DBserver中添加DB
    * @param $DB string.
    * @param $info 表属性
    * @return bool
    * @author dongjiang.dongj
    */
    public function set_DB($DB, $info) {
    }

    /**
    *  create DB.
    * @param $DB string.
    * @return bool
    * @author dongjiang.dongj
    */
    public function createDB($DB) {
    }    

    /** 
    *  DBserver中删除db
    * @param $table string.
    * @return bool
    * @author dongjiang.dongj
    */
    public function delete($table) {
    }
    
    /** 
    *  DBserver中db的属性信息
    * @param $table string.
    * @return bool
    * @author dongjiang.dongj
    */
    public function getDBInfo($table) {
    }

    /**
    * 析构方法
    * @author dongjiang.dongj
    */
    public function __destruct() {
        //TODO
        parent::__destruct();
    }
}
?>
