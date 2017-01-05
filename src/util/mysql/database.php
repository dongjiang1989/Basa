<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : DataBase.php
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
class DataBase extends mysqlBase{

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
    * 获得DB中的table数据
    * @return array.
    * @author dongjiang.dongj
    */
    public function getTables(){
    }

    /**
    *  判断DB中是否有table
    * @param $table string.
    * @return bool.
    * @author dongjiang.dongj
    */
    public function has_table($table) {
        return True;
    }

    /** 
    *  DB中添加table字段
    * @param $field string.
    * @param $info 表属性
    * @return bool
    * @author dongjiang.dongj
    */
    public function set_table($table, $info) {
    }
    
    /** 
    *  DB中删除table
    * @param $table string.
    * @return bool
    * @author dongjiang.dongj
    */
    public function delete($table) {
    }
    
    /** 
    *  DB中table字段的属性信息
    * @param $table string.
    * @return bool
    * @author dongjiang.dongj
    */
    public function getTableInfo($table) {
    }

    /**
    *  create Table.
    * @param $table string.
    * @return bool
    * @author dongjiang.dongj
    */
    public function createTable($table) {
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
