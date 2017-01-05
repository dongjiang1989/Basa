<?php
declare(encoding='UTF-8');
namespace swift {
    /**
    * swift 基本处理：
    *    包括: 查询swift、插入swift、更新swift
    * @author dongjiang.dongj@alibaba-inc.com
    * @TODO: 其他相关方法
    */
    require_once(dirname(__FILE__)."/../../com/util.php");
    require_once(dirname(__FILE__)."/../../com/Eobject.php");

    if (!class_exists('Swift')) {
        /**
        * Swift tools类
        *   实现基本配置管理和，基本的cmd命令执行
        * @author dongjiang.dongj
        */
        class Swift extends \Eobject {
    
            private $_swiftdir = null;
            private $_zookeeper = null;

            private $_cmd = null;  # set swift cmd

            /**
            * 实现构造方法
            * @author dongjiang.dongj
            */
            function __construct($swiftdir, $zookeeper, $host="127.0.0.1") 
            {
                parent::__construct($host);
                //TODO
                $this->_swiftdir = $swiftdir;
                $this->_zookeeper = $zookeeper;

                $this->_cmd = $this->_swiftdir." -z ".$this->_zookeeper;

                updated_data_TS(); // 上传TS
            }

            /**
            * 重载系统方法__get
            * @author dongjiang.dongj
            */
            function __get($property_name) {
                \logging::info("class ".__CLASS__." : get private attribute $property_name");
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
                \logging::info("class ".__CLASS__." : set private attribute $property_name");
                $this->$property_name = $value;
            }

            /**
            * public 方法. help方法
            * @return bool
            * @author dongjiang.dongj
            */
            public function help() {
                $ret = $this->run($this->_swiftdir);
                if (count($ret) == 2 && $ret[0] == 0 && gettype($ret[1])=="array") {
                    return ArrayToString($ret[1], "\n")."\n";
                } else {
                    \logging::error("Can not find Swift Help Message!!! pls, check?!");
                    return null;
                }
            }

            /**
            * public 方法, send msg 到 swift 
            * @param string $topic. 如："solor_pb"
            * @param string $message. 如："aaa=1&bb=2"
            * @param string $hashField. 主key，hash使用
            * @param string $partition. default: 0
            * @return bool
            * @author dongjiang.dongj
            */

            public function send() {
                if (func_num_args() == 4 ) {
                    if (is_file(func_get_arg(1))) {
                        return $this->SendFromFile(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
                    } else {
                        return $this->SendFromMsg(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
                    }
                } else if (func_num_args() == 3 ) {
                    if (is_file(func_get_arg(1))) {
                        return $this->SendFromFile(func_get_arg(0), func_get_arg(1), func_get_arg(2), 0);
                    } else {
                        return $this->SendFromMsg(func_get_arg(0), func_get_arg(1), func_get_arg(2), 0);
                    }
                } else {
                    throw new \AbstractInterface(__CLASS__." arges num is not 4 or 3!", \RETTYPE::ERR);
                }
            }

            private function SendFromMsg($topic, $message, $hashField, $partition=0) {
                try {
                    $_cmd = $this->_swiftdir." send --zookeeper ".$this->_zookeeper." --topic ".$topic." --message ".$message." -hashField ".$hashField." --partition ".$partition;
                    $ret = $this->run($_cmd);
                    return count($ret) == 2 && $ret[0] == 0;
                } catch ( \Exception $e) {
                    \logging::error("Send swift Msg Error! msg:", $e->getMessage() , "pls check?!");
                    return false;
                }
            }
            
            private function SendFromFile($topic, $file, $hashField, $partition=0) {
                try {
                    $_cmd = $this->_swiftdir." send --zookeeper ".$this->_zookeeper." --topic ".$topic." --fileName ".$file." -hashField ".$hashField." --partition ".$partition;
                    $ret = $this->run($_cmd);
                    return count($ret) == 2 && $ret[0] == 0;
                } catch ( \Exception $e) {
                    \logging::error("Send swift Msg Error! msg:", $e->getMessage() , "pls check?!");
                    return false;
                }
            }
 
            /**
            * public 方法. Get msg from swift
            * @param string $tablename. 表名
            * @return bool
            * @author dongjiang.dongj
            */
            public function get() {
                if (func_num_args() == 4 ) {
                    return $this->getMessage(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
                } else if (func_num_args() == 3) {
                    return $this->getMessage(func_get_arg(0), func_get_arg(1), func_get_arg(2));
                } else if (func_num_args() == 2) {
                    return $this->getMessage(func_get_arg(0), func_get_arg(1));
                } else {
                    throw new \AbstractInterface(__CLASS__." arges num is not 4 or 3 or 2!", \RETTYPE::ERR);
                }
            }

            private function getMessage($topic, $partitionId, $count=1, $mode="tail") {
                $seqId = null;
                if (strcmp(strtolower($mode), "head")==0 || strcmp(strtolower($mode), "tail")==0) {
                    $startTimestamp = "";
                    if ( strcmp(strtolower($mode), "head") == 0 ) {
                        $startTimestamp = "1262275201000000";
                    }
                    $maxSeqIds = $this->getMaxMessageId($topic, $startTimestamp);
                    if ($maxSeqIds === null) {
                        return null;
                    }
                    if (!in_array((int)$partitionId, array_keys($maxSeqIds))) {
                        return null;
                    }
                    if ( strcmp(strtolower($mode), "head") == 0 ) {
                        $seqId = (string)$maxSeqIds[$partitionId];
                        $maxs = $this->getMaxMessageId($topic);
                        if ($count > $maxs[$partitionId]) {
                            $count = $maxs[$partitionId];
                        }
                    } else {
                        $seqIdInt = (int)$maxSeqIds[$partitionId] + 1 - (int)$count;
                        if ($seqIdInt < 0) {
                            $seqIdInt = 0;
                            $count = (int)$maxSeqIds[$partitionId];
                        }
                        $seqId = (string)$seqIdInt;
                    }
                } else {
                    $seqId = $mode;
                }
                $_cmd = $this->_swiftdir." gm -z ".$this->_zookeeper." --topic ".$topic." --partition ".$partitionId." --count ".$count." --seqId ".$seqId;
                $ret = $this->run($_cmd);
                if (count($ret) == 2 && $ret[0] == 0 && gettype($ret[1]) == "array") {
                    $_ret=array();
                    $_i = 0;
                    foreach($ret[1] as $v) {
                        if (trim($v) == "") {
                            continue;
                        }
                        $_ret[(int)$seqId + $_i] = $v;
                        $_i = $_i + 1;
                    }
                    return $_ret;
                } else {
                    return null;
                }
            }

            private function getMaxMessageId($topic, $timestamp="") {
                $_cmd = $this->_swiftdir." gs -z ".$this->_zookeeper." --topic ".$topic;
                if (strlen((string)$timestamp) < 10 && strlen((string)$timestamp) > 0) {
                    $timestamp = (string)$timestamp.str_repeat("0", 10-strlen((string)$timestamp));
                    $_cmd = $_cmd." --timestamp ".$timestamp;
                } else if (strlen((string)$timestamp) >= 10) {
                    $_cmd = $_cmd." --timestamp ".$timestamp;
                }
                $ret = $this->run($_cmd);
                $_ret = array();
                if (count($ret) == 2 && $ret[0] == 0 && gettype($ret[1]) == "array") {
                    foreach($ret[1] as $v) {
                        if (trim($v) == "") {
                            continue;
                        }
                        $__tp = StringToArray(trim($v), ",");
                        $_ret[(int)$__tp[0]] = $__tp[1];
                    }
                    return $_ret;
                } else {
                    return null;
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
                    throw new \CallFunctionFail("Tried to call unknown method".get_class($this).'::'.$f, \RETTYPE::ERR);
                }   
            }     

            /**
            * 析构方法
            * @author dongjiang.dongj
            */    
            public function __destruct(){
                parent::__destruct();
            }
        }
    }
}
?>
