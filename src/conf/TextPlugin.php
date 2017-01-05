<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : TextPlugin.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-11-29 19:13
*   Description   : 通用Text解析类，支持分级和缩进
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/ConfIPlugin.php");
require_once(dirname(__FILE__)."/../com/file.php");

/**
* KeyNotFound Exception
* @author dongjiang.dongj
*/
class KeyNotFound extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }   
    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }   
}

/**
* KeyIndexOutOfSize Exception
* @author dongjiang.dongj
*/
class KeyIndexOutOfSize extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }   
    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }   
}

/**
* SectionIndexOutOfSize Exception
* @author dongjiang.dongj
*/
class SectionIndexOutOfSize extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }   
    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }   
}

/**
* 通用Text解析类，支持分级和缩进
* @author dongjiang.dongj
*/
class TextPlugin extends file implements ConfIPlugin {
    const MODE = "text";
    const CLASSNAME = __CLASS__;
    private $_sep = ':'; #默认分割
    private $_blank = True; #空格
    private $_prefix = "\t"; #缩进符
    private $_duplicate_section; #判断相同的section

    private $_md5sumValue;  #保存本文件的md5sum值

    /**
    * 析构方法
    * @param filename: 文件对象的名称
    * @param host: 文件对象所属机器,可支持远程对象
    * @param init_from: 如果不为空，使用本附件进行构建
    * @param auto_bak: 是否需要备份
    * @param duplicate_section: 是否扫描统一名称的section
    * @author dongjiang.dongj 
    */
    public function __construct($filename, $host="127.0.0.1", $init_from="", $auto_bak=False, $duplicate_section=False){
        parent::__construct($filename=$filename, $host=$host, $init_from=$init_from, $auto_bak=$auto_bak);
        //TODO
        $this->_duplicate_section = $duplicate_section;
        $this->_md5sumValue = $this->md5sum();
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
    * private 方法，获得指定section范围
    * @param _get_section: getSections时返回行号区间包含子section(范围更大)
    * @param _get_key    : 用于有多个[GLOBAL]的情况，返回包含需要修改的key的[GLOBAL]的范围
    * @author dongjiang.dongj
    */
    private function getRange( $section, $_get_key="") {
        return $this->_getRange( $section=$section, $_get_section=False, $_get_key=$_get_key );
    }

    /**
    * private 方法，获得指定section范围
    * @param _get_section: getSections时返回行号区间包含子section(范围更大)
    * @param _get_key    : 用于有多个[GLOBAL]的情况，返回包含需要修改的key的[GLOBAL]的范围
    * @author dongjiang.dongj
    */
    private function _getRange( $section, $_get_section, $_get_key="") {
        $begin = 1;
        $end = $this->lines();

        if ($section == "") {
            $_end = $this->sed("-n", "'/^\s*\[/{=;q}'");
            if ($_end != array() && $_end[0] != "" && ($_get_section !== "")) {
                $end = (int)$_end[0] - 1;
            }
            return array((string)$begin.",".(string)$end, 0, "");
        }
        $secs = explode(".", $section);

        #前置空格或tab缩进,以第一个section缩进为准
        $_prefix_t = $this->_prefix; 
        $deep = 0; # section深度(层)

        foreach($secs as $sec) {
            $_prefix_t = "";
            $_prefix_c = str_repeat(".", $deep); #前导"."
            if ( $deep > 0 ) {
                $_prefix_t = "\\".str_repeat($this->_prefix, $deep);
            }
            $deep += 1;
            if (substr($sec, strlen($sec)-1, 1) != ']') { #非数组
                $index = 0;
                $_sec = $_prefix_c.$sec;
            } else {
                # on php 5.4
                # Array(1,2,3)[2];
                # on php 5.3
                list($_tpstr, $index) = explode("[", substr($sec, 0, strlen($sec)-1)); #解析数组index,不考虑多维
                $index = (int)$index;
                $_sec = $_prefix_c."@".$_tpstr;
            }
            
            $_sed = "{/^[ \t]*\[".$_sec."\]/=}"; #获取匹配的section行号
            $_range = (string)$begin.",".(string)$end;

            $output = $this->sed("-n", "'".$_range.$_sed."'");
            $out = $output;

            if ($output != array()) {
                $size = count($out);
            } else {
                $size = 0;
            }
            
            if ($size < $index + 1) { #未找到则在末尾追加
                if ($size < $index) {
                    throw new SectionIndexOutOfSize( "section ".$section." size(".$size.") < index(".$index."), add ".$_sec."[".($size+1)."] before\n", RETTYPE::ERR);
                }
                logging::warn("section '".$sec."' is not exist in ".$this->fullname()."(".$_range.")");

                if ($end == 0) {
                    parent::add("'".$_prefix_t."[".$_sec."]'");
                } else {
                    $this->sed("-i", "'".$end."a".$_prefix_t."[".$_sec."]'");
                }
                $end += 1;
                $begin = $end;
            } else if ( substr($sec, strlen($sec)-1, 1) != ']' && $_get_key != "") {
                $oldend = $end;
                foreach($out as $_key=>$_out) {
                    $begin = (int)$_out + 1;

                    if ( $size != ($_key + 1) ) { #最后一个
                        $end = (int)$out[$_key+1] - 1;
                    } else {
                        $end = $oldend;
                    }
                    $_range = (string)$begin.",".(string)$end;
                    #end位置在下一个section的开始
                    if ($deep == count($secs) && !$_get_section) {
                        $_next_sec = "\[";
                    } else {
                        $_next_sec = "\[".$_prefix_c."[@a-zA-Z]";
                    }
                    $output = $this->sed("-n", "'".$_range."{/^\s*".$_next_sec."/{=;q}}'");
                    if ($output != array()) {
                        $end = (int)$output[0] - 1;
                    }
                    $_key_sec = $_get_key[0];
                    $_range = (string)$begin.",".(string)$end;
                    $output = $this->sed("-n", "'".$_range."{/^\s*".$_key_sec."/{=;q}}'");
                    if ($output != array()) 
                        break;
                }
            } else {
                $begin = (int)$out[$index] + 1;
                if ($size != $index + 1) {
                    $end = (int)$out[$index+1] - 1;
                }
                $_range = (string)$begin.",".(string)$end;
                #end位置在下一个section的开始
                if ($deep == count($secs) && !$_get_section) {
                    $_next_sec = "\[";
                } else {
                    $_next_sec = "\[".$_prefix_c."[@a-zA-Z]";
                }
                $output = $this->sed('-n', "'".$_range."{/^\s*".$_next_sec."/{=;q}}'");
                if ($output != array()) {
                    $end = (int)$output[0] - 1;
                }
            }
        }
        logging::debug("section '".$section."' range is (".$begin.",".$end.")");
        assert($end >= $begin - 1);
        return array((string)$begin.",".(string)$end, $deep, $_prefix_c);
    }

    /**
    * private 方法. 将 key value解析成array
    * @param $section string. 请求使用section，单个标签下key-value对；不设置，使用整个文件中的key-value对
    * @param $sep string. default = ":", key-value对的区分符号
    * @return array
    * @authoe dongjiang.dongj
    */
    private function getArray($section="", $sep=":") {
        list($_range, $_prefix_n, $_deep) = $this->getRange( $section=$section );
        
        $_data = $this->sed('-n', "'".$_range."p'");
        $ret = array();
        $lines = $_data;
        foreach($lines as $line) {
            list($_line,) = explode('#', $line); #去除注释
            $_pos = strrpos($_line, $sep); # 0 和 false 一样
            if ($_pos > 0) {
                $key = trim(substr($_line, 0, $_pos));
                $value = trim(substr($_line, $_pos+1));
                if (substr($key, 0, 1) == "@" && strlen($key) >= 2) {
                    if (array_key_exists($key, $ret)) {
                        $ret[$key][] = $value;
                    } else {
                        $ret[$key] = array($value);
                    }
                } else if ( strlen($key) >= 1 && substr($key, 0, 1) != "$" ) { #一般的key/value,排除$include
                    if (array_key_exists($key, $ret)) {
                        logging::warn("\"".$key."\" is not only in section \"".print_r($ret[$key], true)."\" with \"".$value."\"");
                    }
                    $ret[$key] = $value;
                } else {
                }
            }
        }
        return $ret;
    }

    /**
    * 取parent_section内，指定数组section的size
    * @param parent_section string. 输入夫节点section
    * @param section. 本section中的大小
    * @return int
    * @author dongjiang.dongj
    */
    public function getSectionSize($section, $parent_section="") {
        $sections = $this->_getSections( $parent_section = $parent_section);
        $size = 0;
        $_len = strlen($section);
        foreach( $sections as $sec) {
            if ( substr($sec, strlen($sec)-1, 1) == "]" && substr($sec, 0, $_len) == $section && $section !== "" )
                $size += 1;
        }

        logging::debug($section, "size=", $size);
        return $size;
    }

    /**
    * private方法，取parent_section内的所有子section列表，数组section包含下标
    * @param parent_section string. 输入夫节点section
    * @return array. 获得取parent_section内的所有子section列表
    * @author dongjiang.dongj
    */
    private function _getSections($parent_section="") {
        list($_range, $_deep, $_prefix_c) = $this->_getRange( $section=$parent_section, $_get_section=True, $_get_key="");
        $_data = $this->sed('-n', "'".$_range."p'");
        logging::debug("deep:", $_deep, "range:", $_range, "_data=", $_data);

        $ret = array();
        $_tmp = array();
        foreach ($_data as $line) {
            $line = trim($line);
            if (strlen($line) < $_deep + 2) {
                continue;
            }
            $_sec_prefix = "[".str_repeat(".", $_deep); #前导"."
            if (substr($line, strlen($line)-1, 1) === "]") {
                if ( substr($line, $_deep+1, 1) != "." && substr($line, 0, $_deep+1) == $_sec_prefix ) {
                    $sec = substr($line, $_deep+1, strlen(substr($line, $_deep+1))-1);
                    if ( substr($sec, 0, 1) == "@" && strlen($sec) >= 2) {
                        if (array_key_exists($sec, $_tmp)) {
                            $size = $_tmp[$sec] + 1;
                        } else {
                            $size = 0;
                        }
                        $_tmp[$sec] = $size;
                        $sec = substr($sec, 1)."[".(string)$size."]";
                    }

                    if ($sec != "") {
                        array_push($ret, $sec);
                    }
                } else {
                    logging::warn($line,"seem to be a section, but not");
                }
            }
        }
        return $ret;
    }

    /**
    * 获得指定section下的key-value对
    * @param section string.
    * @param sep string. 分隔符
    * @return array
    * @author dongjiang.dongj
    */
    public function items($section='', $sep=':') {
        return $this->getArray( $section=$section, $sep=$sep);
    }

    /**
    * 获得指定section下的所有keys
    * @param section string.
    * @param sep string. 分隔符
    * @return array
    * @author dongjiang.dongj
    */
    public function keys($section='', $sep=':') {
        return array_keys($this->getArray( $section=$section, $sep=$sep));
    }

    /**
    * 获得指定section下的所有values
    * @param section string.
    * @param sep string. 分隔符
    * @return array
    * @author dongjiang.dongj
    */
    public function values($section='', $sep=':') {
        return array_values($this->getArray( $section=$section, $sep=$sep));
    }

    /**
    *   conf通用set方法，可用sep指定分割符，args和kwds包含设置的key/value对，如：
    *           $conf->set("LOG_LEVEL",'0x10');
    *           $conf->set('abc', 123, 'LOG_LEVEL', '0x10', 'sep', ":");
    *           $conf->set('a[1]', 'bb')    #数组key的set方式
    *
    *   数组形式的value，可用数组形式:
    *           $conf->set( 'section', 'test', 'aaa', array('22', '33') );    #将添加@aa : 22, @aa : 33 ...
    *
    *   指定section 和 指定sep，如：
    *           $conf->set('section', 'abc[1]', 'ttt', 'mmm', 'cc', 123);
    *
    *   若找不到section或key都会进行add, 可用key=None删除指定key，如:
    *           $conf->set( 'LOG_LEVEL', null ) ;   #delete 指定key
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

    private function setN($args) {
        if (array_key_exists("section", $args)) {
            $section = $args["section"];
            unset($args["section"]);
        } else {
            $section = "";
        }

        if (array_key_exists("sep", $args)) { 
            $sep = $args["sep"];
            unset($args["sep"]);
        } else {
            $sep = $this->_sep;
        }
        
        $blank = $this->_blank;
        if (array_key_exists("blank", $args)) {
            $blank = $args["blank"];
            unset($args["blank"]);
        }

        $_range = "";
        $_deep = 0;
        $_prefix_c = "";

        if ($this->_duplicate_section === False) {
            list($_range, $_deep, $_prefix_c) = $this->getRange($section=$section, $_get_key="");
        } else {
            logging::debug("It should have repeated sections");
            list($_range, $_deep, $_prefix_c) = $this->getRange( $section=$section, $_get_key=array_keys($args));
        }

        $_prefix = $this->_prefix;
        if ($_deep > 0) {
            $_prefix = "\\".str_repeat($this->_prefix, $_deep-1);
        }
    
        foreach($args as $key=>$value) {
            logging::debug("set key '".$key."' to value '".$value."', type: ".gettype($value));
            if(substr($key, strlen($key)-1, 1) == "]") {
                list($__tmp0, $__tmp1) = explode("[", substr($key, 0 , strlen($key)-1));
                $index = (int)$__tmp1;
                $key = "@".$__tmp0;
            } else {
                $index = 0;
            }

            if (is_array($value)) {
                assert(substr($key, 0, 1) != "@" && substr($key, strlen($key)-1, 1) != "]");
                try {
                    $_ori = array();
                    $_ori = $this->get($key=$key, $sep=$sep, $section=$section);
                } catch(KeyNotFound $e) {}
                $_bg = 0;

                #直接set数组key每个item
                foreach($value as $val) {
                    logging::debug("set array ".$key."[".$_bg."]");
                    $_tmpkey = $key."[".$_bg."]";
                    $this->set($_tmpkey, $val, 'section', $section, 'sep', $sep);
                    $_bg += 1;
                }

                #将原来的多余item删除
                if($_bg < count($_ori)) {
                    for($t=0; $t<(count($_ori)-$_bg); $t++) {
                        $_tmpkey = $key."[".$_bg."]";
                        $this->delete($_tmpkey, $section, $sep);
                    }
                }
                continue;
            }
            if($blank) {
                $val = $_prefix.$key." ".$sep." ".(string)$value;
            } else {
                $val = $_prefix.$key.$sep.(string)$value;
            }

            $output = $this->sed("-n", "'".$_range."{/^\s*".$key."\s*".$sep."/=}'");
            $out = $output;
            if($output != array()) {
                $size = count($out);
            } else {
                $size = 0;
            }
            
            if($size < $index + 1) { #没有找到key替换
                if ($value === null) { # for detele 方法
                    logging::warn($key." not exist, do not need delete!");
                    return null;
                }
                
                if ($size < $index) {
                    throw new KeyIndexOutOfSize($key." size(".$size.") < index(".$index."), add ".$key."[".($size + 1)."] before", RETTYPE::ERR);
                } else {
                    logging::warn("'".$key."' not exist in '".$_range."', add it to '".$value."'");
                    list(,$_end) = explode(',', $_range);
                    if ($_end == '0') {
                        parent::add($data=$val);
                    } else {
                        $this->sed("-i", "'".$_end."a".$val."'");
                    }
                }
            } else {
                if ($value === null) { # for detele 方法
                    $this->sed("-i", "'".$out[$index]."d'");
                } else {
                    $this->sed("-i", "'".$out[$index]."c".$val."'");
                }
            }
        }
        return True;
    }

    /**
    * 查询是否存在$key，在指定section中
    * @param $key string. 
    * @param $sep string. default=":"
    * @param $section string. default = ""
    * @return bool
    * @author dongjiang.dongj
    * @warn AbstractInterface异常
    */
    public function has_key() {
        if (func_num_args() == 1 ) {
            return in_array(func_get_arg(0), $this->keys($section="", $sep=':'));
        } 
        else if (func_num_args() == 3 ) {
            return $this->has_key3(func_get_arg(0), func_get_arg(1), func_get_arg(2));
        } else {
            throw new AbstractInterface(__CLASS__." arges num is not 1 or 3!", RETTYPE::ERR);
        }
    }    

    private function has_key3($key, $sep=':', $section='') {
        return in_array($key, $this->keys($section=$section, $sep=$sep));
    }
    
    /**
    * 重写 get 方法. conf通用get方法，可以指定分割符sep和所属段section,如:
    *            $conf->get('aaa')
    *            $conf->get('LOG_LEVEL',sep='=')
    *            $conf->get('ccc',section='GLOBAL')
    *            $conf->get('aa[1]',section='GLOBAL.AAA') #数组key获取
    *            $conf->get('@aa', section='GLOBAL.AAA' ) # 返回数组aa的所有值array()
    * @param key
    * @param sep
    * @param section
    * @return array or string
    * @author dongjiang.dongj
    * @warn 可能会KeyNotFound，或者KeyIndexOutOfSize 异常抛出
    */
    public function get() {
        if (func_num_args() == 3 ) {
            return $this->get3(func_get_arg(0), func_get_arg(1), func_get_arg(2));
        } else if (func_num_args() == 1 ) {
            return $this->get3(func_get_arg(0), ":", "");
        } else {
            throw new AbstractInterface(__CLASS__." arges num is not 1 or 3!", RETTYPE::ERR);
        }
    }

    /**
    * 重写 get 方法. conf通用get方法，可以指定分割符sep和所属段section,如:
    *            $conf->get('aaa')
    *            $conf->get('LOG_LEVEL',sep='=')
    *            $conf->get('ccc',section='GLOBAL')
    *            $conf->get('aa[1]',section='GLOBAL.AAA') #数组key获取
    *            $conf->get('@aa', section='GLOBAL.AAA' ) # 返回数组aa的所有值array()
    * @param key
    * @param sep
    * @param section
    * @return array or string
    * @author dongjiang.dongj
    * @warn 可能会KeyNotFound，或者KeyIndexOutOfSize 异常抛出
    */
    private function get3($key, $sep=":", $section='') {
        $index = null;
        if ( substr($key, strlen($key)-1, 1) == "]" ) { #数组get
            list($__tmp0, $__tmp) = explode("[", substr($key, 0, strlen($key)-1));
            $index = (int)($__tmp);
            $key = "@".$__tmp0;
        }
        $_dict = $this->getArray($section=$section, $sep=$sep);

        if (!array_key_exists($key, $_dict)) {
            throw new KeyNotFound("key ".$key." not found in '".$section."'", RETTYPE::ERR);
        }
    
        if ($index !== null) { # 强等于null
            logging::debug("get array key ".$key."[".$index."] from ".$section);
            if (!is_array($_dict[$key]) || $index >= count($_dict[$key])) {
                throw new KeyIndexOutOfSize("key ".$key."[".$index."] not found in ".$section, RETTYPE::ERR);
            } else {
                return $_dict[$key][$index];
            }
        }
        return $_dict[$key];
    }

    function __get($property_name) {
        logging::info("class ".__CLASS__." : get private attribute $property_name");
        if (isset($this->$property_name)){
            return $this->$property_name;
        }   
        else {
            return null;
        } 
    }

    /*  
    * 设置缩进前导符, key/value前后是否有blank，分割符
    * @author dongjiang.dongj
    */
    public function setAttr($sep=':', $blank=True, $prefix="\t" ) {
        $this->_prefix = $prefix;
        $this->_sep = $sep;
        $this->_blank = $blank;
    }

    /**
    *  删除指定section中的key
    * @param $key 指定的key
    * @param $section 指定section. default= ""
    * @param $sep  指定分隔符. sep=":"
    * @return bool
    * @author dongjiang.dongj
    * @warn maybe AbstractInterface 异常；
    */
    public function delete() {
        if (func_num_args() == 3 ) {
            return $this->delete3(func_get_arg(0), func_get_arg(1), func_get_arg(2));
        } else if (func_num_args() == 1 ) {
            return $this->delete3(func_get_arg(0), "", ":");
        } else {
            throw new AbstractInterface(__CLASS__." arges num is not 1 or 3!", RETTYPE::ERR);
        }
    }

    private function delete3($key, $section="", $sep=":") {
        return $this->set( $key, null, 'section', $section, "sep", $sep);
    }

    /**
    *     在指定的section中增加key/value
    * @param $key 指定的key
    * @param $value  指定的value
    * @param $section 指定section. default= ""
    * @param $sep  指定分隔符. sep=":"
    *
    * @return bool
    * @author dongjiang.dongj
    * @warn maybe AbstractInterface 异常；
    */
    public function add() {
        if (func_num_args() == 4 ) {
            return $this->add4(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
        } else if (func_num_args() == 2 ) {
            return $this->add4(func_get_arg(0), func_get_arg(1));
        } else {
            throw new AbstractInterface(__CLASS__." arges num is not 4 or 2!", RETTYPE::ERR);
        }
    }

    public function add4($key, $value, $section='', $sep=':') {
        return $this->set( $key, $value, "section", $section, "sep", $sep);
    }

    /**
    *  在已有key后追加value，如对SERVER: A,B
    *        $conf->iset('SERVER',',ABC');    #改为SERVER: A,B,ABC
    *   数字类型，可实现加，如COUNT : 2
    *        $conf->iset('COUNT', 2);        #conf改为COUNT : 4
    * @param $key 指定的key
    * @param $value  指定的value
    * @param $sep  指定分隔符. sep=":"
    * @param $section 指定section. default= ""
    *
    * @return bool
    * @author dongjiang.dongj
    * @warn maybe AbstractInterface 异常；
    */
    public function iset() {
        if (func_num_args() == 4 ) {
            return $this->iset4(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
        } else if (func_num_args() == 2 ) {
            return $this->iset4(func_get_arg(0), func_get_arg(1));
        } else {
            throw new AbstractInterface(__CLASS__." arges num is not 4 or 2!", RETTYPE::ERR);
        }
    }

    private function iset4($key, $value, $sep=":", $section="") {
        try {
            $_value = $this->get3($key=$key, $sep=$sep, $section=$section);
        } catch (KeyNotFound $e) {
            $_value = "";
        }

        if ( is_int($value) )  {
            $_value = (int)$_value + $value;
        } else {
            $_value = $_value.$value;
        }
        return $this->set( $key, $_value, "section", $section, "sep", $sep);
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

    public function __destruct() {
        //TODO
        parent::__destruct();
    }
}
?>
