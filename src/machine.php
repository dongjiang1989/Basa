<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/com/util.php");
require_once(dirname(__FILE__)."/com/Eobject.php");
class Machine extends Eobject {

    /**
    *   machine 获得机器相关的信息
    * @param $host 模块所在机器
    * @author dongjiang.dongj
    */
    public function __construct( $host="127.0.0.1" ) {
        parent::__construct($host);
        //TODO
    }

    /**
    *   获得机器IP信息
    *     $ret = Machine::getIp(); # 10.125.51.188
    * @return string; IP
    * @warn 如果解析失败，返回false
    * @author dongjiang.dongj
    */
    static public function getIp() {
        $obj = new Machine("127.0.0.1");
        $ret = $obj->run("hostname -i");
        if (count($ret) == 2 && $ret[0] == "" && count($ret[1]) == 1 && 
            preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ret[1][0])) {
            return $ret[1][0];
        } else {
            $ret = $obj->run("php -r \"echo gethostbyname(gethostname());\"");
            if (count($ret) == 2 && $ret[0] == "" && count($ret[1]) == 1 && 
                preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ret[1][0])) {
                return $ret[1][0];
            }
            return false;
        }
    }

    
    /**
    *   获得机器hostname信息
    *    $ret = Machine::getHostname(); # v125051188.bja
    * @return string; hostname
    * @warn 如果解析失败，返回false
    * @author dongjiang.dongj
    */
    static public function getHostname() {
        $obj = new Machine("127.0.0.1");
        $ret = $obj->run("hostname");
        if (count($ret) == 2 && $ret[0] == "" && count($ret[1]) == 1 && $ret[1][0] != "") {
            return $ret[1][0];
        } else {
            $ret = $obj->run("php -r \"echo gethostname();\"");
            if (count($ret) == 2 && $ret[0] == "" && count($ret[1]) == 1 && $ret[1][0] != "") {
                return $ret[1][0];
            }
            return false;
        }
    }

    /** 析构方法 */
    public function __destruct(){
        parent::__destruct();
    }
}

?>
