<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : Json.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-12-12 19:13
*   Description   : 通用Json解析类
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../../com/util.php");
/**
* Json通用解析方法
*      支持块的分级,查找,替换和缩进等
* @author dongjiang.dongj
*/
class Json extends Object {

    private $need_sort; 
    private $depth;
    private $options;

    static private $ISENCODETOGBK = False;
    /**
    * Json构造方法
    *   实现json的基本方法
    * @param sort_keys bool: 是否按照key进行sort
    * @param encoding encodeType: 将数据encoding to utf-8
    * @param depth int : 设置json的深度,default=32
    * @param options : Bitmask of JSON decode options. 
    *       Currently only JSON_BIGINT_AS_STRING is supported (default is to cast large integers as floats)
    * @author dongjiang.dongj 
    */
    public function __construct($sort_keys=False, $depth=32, $options=0){
        parent::__construct();
        //TODO
        $this->need_sort = $sort_keys;
        $this->depth = $depth;
        $this->options = $options;
        updated_data_TS();
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

    static public function isReturnGBk($bool = true) {
        self::$ISENCODETOGBK = $bool;
    }

    /** 
    * 重载系统方法__get
    * @author dongjiang.dongj
    */
    function __get($property_name) {
        logging::info("class ".__CLASS__." : get private attribute $property_name");
        if (isset($this->$property_name)){
            return $this->$property_name;
        }   
        else {
            return null;
        }   
    }   

    /** 
    * 载系统方法__set
    * @author dongjiang.dongj
    */
    function __set($property_name,$value) {
        logging::info("class ".__CLASS__." : set private attribute $property_name");
        $this->$property_name = $value;
    } 

    /*
    * 递归将参数编码转换为utf-8编码方式
    * @param arrstr : 需要转换的数据,可以为数组或字符串
    * @return 转换为utf-8后的结果
    * @author yiyan.sxc
    */
    static private function _change2Utf8($arrstr){
        if(is_array($arrstr)){
            $tempArr = array();
            foreach($arrstr as $key=>$val){
                $key = Json::_change2Utf8($key);
                $val = Json::_change2Utf8($val);
                $tempArr[$key] = $val;
            }
            return $tempArr;
        }else{
            $encode = mb_detect_encoding($arrstr, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
            if (gettype($arrstr) == "string" && $encode == 'EUC-CN') {
                $arrstr = iconv('GBK', 'UTF-8', $arrstr);
            }
            return $arrstr; 
        }
    }

    /*
    * 递归将参数编码转换为utf-8编码方式
    * @param arrstr : 需要转换的数据,可以为数组或字符串
    * @return 转换为utf-8后的结果
    * @author yiyan.sxc
    */
    static private function _change2GBK($arrstr){
        if(is_array($arrstr)){
            $tempArr = array();
            foreach($arrstr as $key=>$val){
                $key = Json::_change2GBK($key);
                $val = Json::_change2GBK($val);
                $tempArr[$key] = $val;
            }
            return $tempArr;
        }else{
            $encode = mb_detect_encoding($arrstr, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
            if (gettype($arrstr) == "string" && $encode == "UTF-8") {
                $arrstr = iconv('UTF-8', 'GBK', $arrstr);
            }
            return $arrstr; 
        }
    }

    /*
    * 返回最后发生的 json_decode 错误原因
    * @author yiyan.sxc    
    */ 
    private function get_json_last_error(){
        $error = json_last_error();
        $msg = "";
        switch($error){
            case 'JSON_ERROR_NONE':{
                $msg = "without any error product";
                break;
            }
            case 'JSON_ERROR_DEPTH':{
                $msg = "more than the maximum stack depth";
                break;
            }
            case 'JSON_ERROR_CTRL_CHAR':{
                $msg = "unexpected control character found";
                break;
            }
            case 'JSON_ERROR_STATE_MISMATCH':{
                $msg = "error json string";
                break;
            }
            case 'JSON_ERROR_SYNTAX':{
                $msg = "syntax error";
                break;
            }
            case 'JSON_ERROR_UTF8':{
                $msg = "error utf-8 coding";
                break;
            }
            default:{
                $msg = "unknown error\n";
            }
        }
        return $msg;
    }
 
     /**
    * 实现 json_decode 数据
    * @param json string. 输入string 数据
    * @return array. 返回 json_decode 数据
    * @warn maybe throw InputError or TypeError
    * @author dongjiang.dongj
    */
    //static public function load($json, $sort_keys=False, $depth=32, $options=0){
    static public function Deserialize($json, $sort_keys=False, $depth=32, $options=0){
        
        if (gettype($json) == "string") {
            $obj = new Json($sort_keys=$sort_keys, $depth=$depth, $options=$options);
            $_encode = mb_detect_encoding($json, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
            if (gettype($json) == "string" && $_encode == 'EUC-CN') {
                $json = iconv('GBK', 'UTF-8', $json);
            }
            $ret = json_decode($json, true, $obj->depth);
            if (self::$ISENCODETOGBK == true) {
                $ret = Json::_change2GBK($ret);
            }
            if (gettype($ret) == "NULL") {
                $msg = $obj->get_json_last_error();
                throw new TypeError($json." Deserialize error! msg:".$msg, RETTYPE::ERR);
            } else {
                if ($sort_keys == True) {
                    ksort($ret);
                }
                return $ret;
            }
        } else {
            throw new InputError("Input $json type is not string! type:".gettype($json), RETTYPE::ERR);
        }
    }

    /**
    * 实现 json_encode 操作
    * @param $array array. 输入array
    * @param $options CONST. 
        Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP,\
                              JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT,\
                              JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_UNESCAPED_UNICODE
    * @param $depth int. default=32
    * @return string. 将array 转换为json string
    * @warn maybe throw InputError or TypeError 
    * @author dongjiang.dongj
    */
    static public function Serialize($array, $options = 0, $depth = 32) {

        if (gettype($array) == "array") {
            $arr = Json::_change2Utf8($array);
            $obj = new Json($sort_keys=False, $depth=$depth, $options=$options);
            $str = json_encode($arr, $obj->options);
            if ($str === false) {
                $msg = $obj->get_json_last_error();
                throw new TypeError($json." Serialize error! msg:".$msg, RETTYPE::ERR);
            } else {
                $str = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $str);
                if (self::$ISENCODETOGBK == true && gettype($str) == "string" 
                    && mb_detect_encoding($str, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5')) == "UTF-8")
                {
                    $str = iconv('UTF-8', 'GBK', $str);
                }
                return $str;
            }
        } else {
            throw new InputError("Input $array type is not array! type:".gettype($array), RETTYPE::ERR);
        }
    }

    /**
    * 判断key是否在json中存在, 如果json有多层时只检查最外一层
    * 1、将json串解析为数组;
    * 2、判断指定key是否是数组的键,是则返回true,否则返回false;
    * @param     $key 查询key    $jsonstr 查询的json串
    * @return bool 
    * @author dongjiang.dongj
    */
    static public function hasKey($jsonstr, $key) {
        $obj = new Json($sort_keys=False, $depth=32, $options=0);
        try {
            $jsonArr = $obj->Deserialize($jsonstr);
        } catch (InputError $e) {
            logging::error($e->getmessage());
            return NULL;
        }
        catch( TypeError $e){
            logging::error("json str is error,erro msg is:",$e->getmessage());
            return NULL;    
        }    
        return array_key_exists($key, $jsonArr);    
    }

    /**
    * 获得key的value值,如果json有多层则只检查最外层key对应的value值
    * @param     $key 查询key    $jsonstr : 需要查询的json 串
    * @return string or array
    * @warn KeyNotFound 异常
    * @author dongjiang.dongj
    */
    static public function getValue($jsonstr,$key) {
        $obj = new Json($sort_keys=False, $depth=32, $options=0);
        try {
            $arr = $obj->Deserialize($jsonstr);
        } catch (InputError $e) {
            logging::error($e->getmessage());
            return NULL;
        } catch( TypeError $e){
            logging::error("json str is error,erro msg is:",$e->getmessage());
            return NULL;    
        }    
        if(array_key_exists($key,$arr)){
            return $arr[$key];
        }
        return false;  
    }
    
    /**
    * 获得value的值和类型
    * @param $key 查询key
    * @return array.
    *    like: array($value, $type)
    * @author dongjiang.dongj
    */
    static public function get($jsonstr, $key) {
        $value = Json::getValue($jsonstr, $key);
        $type = Json::getValueType($jsonstr, $key);
        if ($value !== NULL && $type !== NuLL) {
            return array($value, gettype($value));
        } else {
            return NULL;
        }
    }

    /**
    *  获取制定key的value 类型. 只是单层key检测
    * @param $key 指定的key
    * @return type string. like: string array object int float double null
    * @author dongjiang.dongj
    */
    static public function getValueType($jsonstr, $key) {
        $obj = new Json($sort_keys=False, $depth=32, $options=0);
        try {
            $arr = $obj->Deserialize($jsonstr);
        } catch (InputError $e) {
            logging::error($e->getmessage());
            return NULL;
        } catch( TypeError $e){
            logging::error("json str is error,erro msg is:",$e->getmessage());
            return NULL;    
        }    
        if(array_key_exists($key,$arr)){
            return gettype($arr[$key]);
        }
        return NULL;
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
