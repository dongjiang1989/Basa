<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : util.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-11-29 19:13
*   Description : 
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/logging.php");
require_once(dirname(__FILE__)."/IPlugin.php");

/**
* 全局返回的状态码. 此处统一管理
* @author dongjiang.dongj
*/
class RETTYPE {
    const SUCC = 0;
    const ERR  = -1;
    const TIMEOUT = -2;
}

/**
* Class 重定义异常
* @author dongjiang.dongj
*/
class ClassIsExist extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }
}

/**
* Class 不存在
* @author dongjiang.dongj
*/  
class ClassNotExist extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }   
    
    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }
}

/**
* 两台机器建立信任关系异常
* @author dongjiang.dongj
*/
class AuthenticateFail extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }
}

/**
* 类型异常
* @author dongjiang.dongj
*/
class TypeError extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }
}

/**
* 输入异常
* @author dongjiang.dongj
*/
class InputError extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }
}

/**
* 通用function，判断是否是本机
* @param $host string. 可以是IP 或 hostname
* @return bool
* @author dongjiang.dongj
*/
function is_local($host) {
    try{
        return in_array(gethostbyname($host), Array("127.0.0.1",
                                           gethostbyname(gethostname()),
                                           "0.0.0.0"));
    }        
    catch (Exception $e) {
        logging::error("Caught Exception: ".$e->getMessage()."\n");
        return False;
    }
}

/**
* 函数方法重命名. like class_rename
* @param $target 目标方法名
* @param $original 源方法名
* @return void
* @author dongjiang.dongj
*/
function func_alias($target, $original) {
    eval("function $target() { \$args = func_get_args(); return call_user_func_array('$original', \$args); }");
}

/**
* 判断两个host是否是一台机器
* @param $host1，$host2 .可以是IP 或 hostname
* @return bool
* @author dongjiang.dongj
*/
function is_same_host($host1, $host2) {
    $host1 = end(split('@', $host1));
    $host2 = end(split('@', $host2));
    if ($host1 == $host2) {
        return True;
    }
    else if ( gethostbyname($host1) == gethostbyname($host2) ) {
        return True;
    }
    else if ( is_local($host1) && is_local($host2) ) {
        return True;
    }
    else {
        return False;
    }
}

/**
* 判断是否有当前账户与目标机器是否有信任关系
* @return bool
* @author dongjiang.dongj
*/
function is_authenticate($host) {
    system('ssh -o StrictHostKeyChecking=no -o \
            PasswordAuthentication=no '.$host.' true &>/dev/null', $ret);
    return $ret == 0;
}

/**
* array2string
* @param $arr array.
* @param $sep . string分隔符
* @return string.
* @author dongjiang.dongj
*/
function ArrayToString($arr, $sep="\n"){
    return implode($sep, $arr);
}

/**
* string2array
* @param $string string.
* @param $sep . string分隔符 default=" "
* @return array.
* @author dongjiang.dongj
*/
function StringToArray($string, $sep=" "){
    return explode($sep, trim($string, PHP_EOL));
}

/**
* 建立信任关系
* @param $host. ip 或者 hostname
* @return bool
* @author dongjiang.dongj
* @warn 可能会出现AuthenticateFail 建立信任关系失败异常
*/
function build_authenticate($host) {
    $Cmd = "test -e ~/.ssh/id_rsa.pub ||"."{ mkdir -p ~/.ssh && ssh-keygen -t rsa -f ~/.ssh/id_rsa -P '' &>/dev/null;} &&"."cat ~/.ssh/id_rsa.pub ";
    exec( $Cmd, $key, $status );
    if ( $status != 0 || $key == Array() ) {
        throw new AuthenticateFail("get local rsa key fail, status=".$status.", output=".ArrayToString($key), $status);
    }
    else {
        $Cmd = "ssh '".$host."' 'test -d ~/.ssh || mkdir -p ~/.ssh &&"."chmod 755 ~ ~/.ssh && cd ~/.ssh && touch authorized_keys &&"."echo \"".ArrayToString($key, '')."\" >> authorized_keys && chmod 600 authorized_keys '";
        exec( $Cmd, $key, $status );
        if ($status != 0) {
            throw new AuthenticateFail("build authenticates for ".$host." fail, status=".$status.", output=\n".ArrayToString($key), $status);
        }
        return $status == 0;
    }
}

/**
* 上传使用信息到TS
* @param null
* @return bool
* @author dongjiang.dongj
*/
$_Ts_php_common_used = true;
function updated_data_TS() {
    try {
        global $_Ts_php_common_used;
        $ret = false;
        $user = array();
        exec('whoami', $user, $ret);

        if ( $_Ts_php_common_used == true && $ret==0 && count($user) == 1) {
            exec("curl --speed-time 5 --speed-limit 1 \"http://ts.alibaba.net/testsupply/index.php/toolsreportuse/create?tool=php-test-common&host=".gethostname()."&user=".$user[0]."\" 2>/dev/null 1>/dev/null", $o, $s);
        }
        $_Ts_php_common_used = false;
    } catch(exception $e) {
    }
    return true;
}

function safe_base64_encode($string) {
    $data = base64_encode($string);
    return str_replace(array('+','/','='), array('-','_',''), $data);
}

function safe_base64_decode($string) {
    $data = str_replace(array('-','_'), array('+','/'), $string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    return base64_decode($data);
}

function ads_base64_encode($string) {
    $data = base64_encode($string);
    return str_replace(array('+','/','='), array("-", '.', '_'), $data);
}

function ads_base64_decode($string) {
    $data = str_replace(array("-", '.', '_'), array('+','/','='), $string);
    return base64_decode($data);
}

?>
