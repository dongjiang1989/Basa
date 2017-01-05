<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      :YamlPlugin.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-12-12 19:13
*   Description   : 通用Yaml解析类，支持分级和缩进
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/ConfIPlugin.php");
require_once(dirname(__FILE__)."/../com/file.php");
require_once(dirname(__FILE__)."/../tools/spyc/Spyc.php");
/**
* Yaml文件通用解析方法
*   支持块的 分级、查找、替换和缩进等
* @author dongjiang.dongj
*/
class YamlPlugin extends file implements ConfIPlugin {
    const MODE = "yaml";
    const CLASSNAME = __CLASS__;

    private $_md5sumValue;  #保存本文件的md5sum值

    const SEP = "."; # 分割符号

    private $_Yaml;  # 内部变量，保存yaml文件中的所有配置，结构为一个array

    /**
    * Yaml构造方法
    *   实现 yaml 文件load
    * @param filename: 文件对象的名称
    * @param host: 文件对象所属机器,可支持远程对象
    * @param init_from: 如果不为空，使用本附件进行构建
    * @param auto_bak: 是否需要备份， default = True
    * @author dongjiang.dongj 
    */
    public function __construct($filename, $host="127.0.0.1", $init_from="", $auto_bak=True){
        parent::__construct($filename=$filename, $host=$host, $init_from=$init_from, $auto_bak=$auto_bak);
        //TODO
        $this->_md5sumValue = $this->md5sum();
        try {
            $this->_Yaml = Spyc::YAMLLoad($this->fullname());
        } catch( Exception $e ) {
            throw new InitFail("load yaml file: ".$this->fullname()." is fail! Please check yaml file ?!");
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
    * private 方法. 实现 将 array打平节点
    *   如： array('a'=>array('b'=>'c'))   ===> array('a.b'=>'c')
    * @param $key 前段key
    * @param $arr 后端子array
    * @return array 打平节点array数据
    * @author dongjiang.dongj
    */
    private function _KeyInsertValue($key, $arr) {
        $ret = array();
        if (gettype($arr) == 'array') {
            foreach($arr as $k=>$v) {
                if ( gettype($v) == 'array') {
                    $ret[] = $this->_KeyInsertValue($key, $v);
                } else {
                    $ret[] = $key.self::SEP.$v;
                }
            }
        } else {
            $ret[] = $key;
        }
        return $ret;
    }

    /**
    * private 方法. 实现返回array数组
    * @author dongjiang.dongj
    */
    private function _setValue($arr) {
        $ret = array(); #返回一个多层array
        foreach($arr as $k=>$v) {
            if ( gettype($v) != 'array') {
                $ret[(string)$k] = $this->_KeyInsertValue((string)$k, $v);
            } else {
                $ret[(string)$k] = $this->_KeyInsertValue((string)$k, $this->_setValue($v));
            }
        }
        return $ret;
    }
    
    /**
    * private 方法. 递归获得不同深度value, 保存在引用￥output中
    * @author dongjiang.dongj
    */
    private function _getDeepValues($arr, &$output) {
        foreach($arr as $key=>$val) {
            if (gettype($val) != 'array' ) {
                array_push($output, $val);
            } else {
                $this->_getDeepValues($val, $output);
            }
        }
    }

    private function _getkey($output) {
        $_tmp = array();
        foreach($output as $key) {
            $tmparr = explode(self::SEP, $key);
            while($tmparr && count($tmparr)>0) {
                array_pop($tmparr);
                $substr = implode(self::SEP, $tmparr);
                if (!in_array($substr, $output) && !in_array($substr, $_tmp)) {
                    array_push($_tmp, $substr);
                }
            }
        }
        return $_tmp;
    }

    /*
    * 获得Yaml文件中所有的keys
    * @param $arr
    * @return array 返回Yaml文件中所有的keys
    * @author dongjiang.dongj
    */
    public function getKeys($arr) {
        $output = array();
        $_tmp = $this->_setValue($arr);
        $this->_getDeepValues($_tmp, $output);
        $_tmp = $this->_getkey($output);
        //return array_merge($output, $_tmp);
        return array_merge_recursive($output, $_tmp);
    }

    /**
    * private方法. 获得指定数据中的格式数据
    * @author dongjiang.dongj
    */
    private function _getvalue($key, $arr) {
        try {
            if ($key == "") {
                return null;
            } else if(gettype($key) == "array") {
                $_ret = array();
                foreach($key as $k) {
                    $_tmp = "\$ret = \$arr['".str_replace(self::SEP, "']['", $k)."'];";
                    eval($_tmp);
                    $_ret[(string)$k] = $ret;
                }
                return $_ret;
            } else {
                $_ret = array();
                $_tmp = "\$ret = \$arr['".str_replace(self::SEP, "']['", $key)."'];";
                eval($_tmp);
                $_ret[(string)$key] = $ret;
                return $_ret;
            }
        } catch(exception $e) {
            return null;
        }
    }

    /**
    * 通过指定key，在array中查找对应的value
    * @param $key 指定key数据; 如：key="A.B.C"
    * @param $arr array 
    * @return string or array. 如果key存在，唯一返回string, 不唯一返回array; key不存在，返回null;
    * @author dongjiang.dongj
    */
    public function getValues($key, $arr) {
        $retKeys = $this->getKeys($arr);
        $count = 0;
        $matchKey = array();
    
        if(in_array($key, $retKeys)) {
            return $this->_getvalue($key, $arr);
        }

        foreach($retKeys as $val) {
            if ( strpos($val, $key) !== false && 
                 array_intersect(explode(self::SEP, $key), explode(self::SEP, $val) ) == explode(self::SEP, $key) ){
                $count += 1;
                array_push($matchKey, $val);
            }
        }

        if ($count == 1) {
            return $this->_getvalue($matchKey[0], $arr);
        }
        else if ($count == 0) {
            return null;
        } else {
            return $this->_getvalue($matchKey, $arr);
        }
    }

    /**
    * private 方法. 将现有的"修改"的Yaml 数据，写回到文件
    * @dongjiang.dongj
    */
    private function _dump($indent = false, $wordwrap = false) {
        $retString = Spyc::YAMLDump($this->getYaml(), $indent, $wordwrap );
        $this->_md5sumValue = $this->md5sum();
        $_ret = $this->clear();
        if ($_ret[0] == 0) {
            $_tmp = $this->feed($retString);
            return !$_tmp[0];
        } else {
            return false;
        }
    }

    /**
    * 获得当前yaml 数据
    * @author dongjiang.dongj
    */
    private function getYaml() {
        return $this->_Yaml;
    }
    
    /**
    * 重载系统方法，获得class私有变量
    * @author dongjiang.dongj
    */
    public function __get($property_name) {
        logging::info("class ".__CLASS__." : get private attribute $property_name");
        if (isset($this->$property_name)){
            return $this->$property_name;
        }   
        else {
            return null;
        }   
    } 

    /**
    * yaml通用set方法, args包含设置的key/value对：
    *   $yaml->set('aaa', "123");
    *   $yaml->set('aaaa.bbb.ccc'. 'aa');
    *   $yaml->set('aaaa.bbb.ccc'. 'aa', 'A', 1, 'B', 2);
    *
    * 数组形式的value，可用数组形式:
    *   $yaml->set('aaa', array(123)); 
    *
    * 若找不到key都会进行add, 可用key=null删除指定key，如:   
    *   $yaml->set('aaa', null); 
    *
    * @param 输入key-value pair. 因此，必须是偶数个
    * @return bool
    * @warn 如果输入不是偶数个时，会抛出 AbstractInterface 异常； 如果设置异常，会抛出 KeyIndexOutOfSize 异常
    * @author dongjiang.dongj
    */
    public function set() {
        if ( func_num_args()%2 != 0 || func_num_args() == 0) {
            throw new AbstractInterface(__CLASS__." arges num is not even !", RETTYPE::ERR);
        } else {
            $args = func_get_args();
            $args = $this->list_to_assoc($args);
            return $this->setN($args);
        }
    } 

    /** 
    * 将单 array 转 key-value array
    * @author dongjiang.dongj
    */
    private function list_to_assoc($args) {
        $assoc = array();
        while ($args and count($args) > 1) {
            $assoc[(string)array_shift($args)] = array_shift($args);
        }   
        return $assoc;
    } 

    /**
    * private 方法. 将str array 转换为 数组array
    *   如：array('A.B.C', 'aa') ===> array('A'=>array('B'=>array('C'=>"aa")))
    * @author dongjiang.dongj
    */
    private function _str2arr($str, $value) {
        $_tmpkeys = explode(self::SEP, $str);
        $_ret= array();
        $_bg = & $_ret;
        while ($_tmpkeys and count($_tmpkeys) > 1) {
            $_key = array_shift($_tmpkeys);
            $_bg[$_key] = array();
            $_bg = &$_bg[$_key];
        }
        $_key = array_shift($_tmpkeys);
        $_bg[$_key] = $value;
        
        return $_ret;
    }

    private function setN($args) {
        $_retKeys = $this->getKeys($this->getYaml());
        foreach($args as $key=>$value) {
            if (in_array($key, $_retKeys)) {
                if ($value !== null) { # for set
                    $_tmp = "\$this->_Yaml['".str_replace(self::SEP, "']['", $key)."'] = \$value;";
                    eval($_tmp);
                } else {  # for delete
                    $_tmp = "unset(\$this->_Yaml['".str_replace(self::SEP, "']['", $key)."']);";
                    eval($_tmp);
                }
            } else {
                if ($value === null) { # for delete
                    logging::warn($key." is not in yaml file, so do not delete!");
                } else {  # for add
                    $_retArr = $this->_str2arr($key, $value);
                    $this->_Yaml = array_merge_recursive($this->_Yaml, $_retArr);
                }
            }
        }
        return $this->_dump();
    }

    /**
    * Yaml通用get方法, 如:
    *            $conf->get('aaa')
    *            $conf->get('ccc.bbb')
    *            $conf->get('aa.ccc.dddd') # 返回数组aa.ccc.dddd的所有值array()
    * @param key
    * @return array or string
    * @author dongjiang.dongj
    * @warn 可能会KeyNotFound，或者AbstractInterface 异常抛出
    */   
    public function get() {
        if (func_num_args() == 1 ) { 
            return $this->get1(func_get_arg(0));
        } else {
            throw new AbstractInterface(__CLASS__." arges num is not 1!", RETTYPE::ERR);
        }
    }

    public function get1($key) {
        $ret = $this->has_key($key);
        if ($ret === true) {
            return $this->getValues($key, $this->getYaml());
        } else if ($ret === false) {
            throw new KeyNotFound("key ".$key." not found in yaml conf: ".$this->fullname(), RETTYPE::ERR);
        } else {  #如果发现多个匹配，返回一个全集 array
            logging::warn($key." has found more than one keys, return values!");
            return $this->getValues($key, $this->getYaml());
        }
    }

    /**
    * 查询是否存在$key, 在Yaml文件中
    * @param $key string. 
    * @return bool. 如有多个key同时在Yaml文件中，返回null
    * @author dongjiang.dongj
    * @warn AbstractInterface异常
    */
    public function has_key() {
        if (func_num_args() == 1 ) {
            return $this->has_key1(func_get_arg(0));
        } else {
            throw new AbstractInterface(__CLASS__." arges num is not 1!", RETTYPE::ERR);
        }
    }

    public function has_key1($key) {
        $retKeys = $this->getKeys($this->getYaml());
        if (in_array($key, $retKeys)) {
            return True;
        }

        $count = 0;
        $_marchKeys = array();
        foreach($retKeys as $val) {
            if ( strpos($val, $key) !== false && 
                 array_intersect(explode(self::SEP, $key), explode(self::SEP, $val) ) == explode(self::SEP, $key) )
            {
                $count += 1;
                array_push($_marchKeys, $val);
            }
        }
        
        if ($count == 0) {
            return false;
        } else if ($count == 1 ) {
            return true;
        } else {
            logging::warn($key." is not has one , please check !? yaml conf match keys :", $_marchKeys);
            return null;
        }
    }

    /**
    *  删除yaml文件中的指定的key
    * @param $key 指定的key
    * @return bool
    * @author dongjiang.dongj
    * @warn maybe AbstractInterface 异常；
    */
    public function delete() {
        if (func_num_args() == 1 ) {
            return $this->delete1(func_get_arg(0));
        } else {
            throw new AbstractInterface(__CLASS__." arges num is not 1 !", RETTYPE::ERR);
        }
    }

    private function delete1($key) {
        return $this->set($key, null);
    }

    /**
    *   在yaml文件中，增加key/value
    * @param $key 指定的key
    * @param $value  指定的value
    *
    * @return bool
    * @author dongjiang.dongj
    * @warn maybe AbstractInterface 异常；
    */
    public function add() {
        if (func_num_args() == 2 ) {
            return $this->add1(func_get_arg(0), func_get_arg(1));
        } else {
            throw new AbstractInterface(__CLASS__." arges num is not 2 !", RETTYPE::ERR);
        }
    }

    private function add1($key, $value) {
        return $this->set($key, $value);
    }

    /**
    *  在已有key后追加value，如对SERVER: A,B
    *        $yaml->iset('SERVER',',ABC');    #改为SERVER: A,B,ABC
    *   数字类型，可实现加，如COUNT : 2
    *        $yaml->iset('COUNT', 2);        #conf改为COUNT : 4
    * @param $key 指定的key
    * @param $value  指定的value
    *
    * @return bool
    * @author dongjiang.dongj
    * @warn maybe AbstractInterface 异常；
    */
    public function iset() {
        if (func_num_args() == 2 ) {
            return $this->iset2(func_get_arg(0), func_get_arg(1));
        } else {
            throw new AbstractInterface(__CLASS__." arges num is not 2!", RETTYPE::ERR);
        }
    }

    private function iset2($key, $value) {
        try {
            $_tmparr = array_values($this->get($key));
            if ($_tmparr && count($_tmparr) == 1) {
                $_value = $_tmparr[0];
            } else {
                $_value = "";
            }
        } catch (KeyNotFound $e) {
            $_value = ""; 
        }   

        if ( is_int($value) )  {
            $_value = (int)$_value + $value;
        } else {
            $_value = $_value.$value;
        }   
        return $this->set( $key, $_value );
    }

    /**
    * 获取从上一次调用本方法 到本次调用本方法间，判断conf文件是否变化
    * @return bool
    * @author dongjiang.dongj
    */
    public function isChange() {
        if ($this->md5sum() != $this->_md5sumValue) {
            $this->_md5sumValue = $this->md5sum();
            return true;
        } else {
            return false;
        }
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
