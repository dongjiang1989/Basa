<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : Table.php
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
class Table extends mysqlBase{

    /**
    * Table构造方法
    *   实现 Table的基本操作
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
    * 获得表中的字段数据
    * @return array.
    * @author dongjiang.dongj
    */
    public function getFields(){
    }

    /**
    *  判断table中是否有field
    * @param $key string.
    * @return bool.
    * @author dongjiang.dongj
    */
    public function has_field($key) {
        return True;
    }

    /** 
    *  表中添加$field字段
    * @param $field string.
    * @param $info 字段属性
    * @return bool
    * @author dongjiang.dongj
    */
    public function set_field($field, $info) {
    }
    
    /** 
    *  表中删除field字段
    * @param $field string.
    * @return bool
    * @author dongjiang.dongj
    */
    public function delete($field) {
    }
    
    /** 
    *  表中field字段的属性信息
    * @param $field string.
    * @return bool
    * @author dongjiang.dongj
    */
    public function getFieldInfo($field) {
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
