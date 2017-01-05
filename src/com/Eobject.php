<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : eobject.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-10-20 19:13
*   Description : 
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/util.php");
require_once(dirname(__FILE__)."/Object.php");
/**
* Php exec shell Exception
* @author dongjiang.dongj
*/
class ExecuteFail extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }   
    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }   
} 

/**
* Eobject Eval-Object类. 统一执行类，避免多调用关系的方式.
* 功能：
*     1、结局远程和本地差异，实现本地远程调用的一致性.
*     2、对执行类进行封装，实现status，output的输出.
* @author dongjiang.dongj
*/
class Eobject extends Object {

    private $host;
    public function __construct($host){
        parent::__construct();
        $this->host = $host;
        updated_data_TS();
    }

    /**
    * 获得执行主机命令
    * @return 可以是ip，也可以是hostname
    * @author dongjiang.dongj
    */
    public function gethost(){
        return $this->host; 
    }

    /**
    * 重写系统方法.
    * 如果先获得一个class的private变量. 或者是不存在的变量. 避免出现异常
    * @param 输入一个key. 变量名
    * @return 如果class 中 变量存在，直接返回变量值；如果变量不存在，返回Null
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
    * 重写系统方法.
    * 对class设置一个变量，可以是不存在的变量.
    * @param property_name 输入一个key. 变量名
    * @param value 变量的值
    * @return void
    * @author dongjiang.dongj
    */
    function __set($property_name,$value) {
        logging::info("class ".__CLASS__." : set private attribute $property_name");
        $this->$property_name = $value;
    }

    /**
    * 实现本机和远程的屏蔽操作的具体实现
    * @param $cmd 具体执行的shell命令
    * @param $_exc 是否抛异常. default值是 False；如果是True，会throw ExecuteFail
    * @return array($status, Array($output))
    * @author dongjiang.dongj
    * @warn 可能会出现ExecuteFail异常
    */
    public function execute( $cmd, $_exc = False){
        if ( is_bool($_exc) ) {
            if ($_exc === true)
                $_tmp_exc = "true";
            else
                $_tmp_exc = "false";
        }
        $prefix = "[".__class__."] execute command [".$cmd."] _exc [".$_tmp_exc."]";
        logging::debug($prefix);
        unset($output);
        exec( $cmd, $output, $status );
        $_msg = "";
        if (count($output) <= 15)
            $_msg = ArrayToString($output);
        else {
            $_msg = ArrayToString(array_slice($output, 0, 15));
        }
        if ( $_exc == True) {
            if (!in_array($status, Array(0,256))) {
                throw new ExecuteFail($prefix.": fail(".$status." != 0), output:\n".$_msg, RETTYPE::ERR);
            }
            else {
                if ($_msg != "")
                    logging::debug($prefix.": succeed, output:\n".$_msg);
            }
        }
        return Array($status, $output);
    }

    /**
    * rcp : remote cp 方法. 实现本地目录间的拷贝 与 远程机器之间的拷贝.
    *     本地使用cp 远程使用 lftp 实现. 
    * @param src string. 源路径
             如：127.0.0.1:/home/dongjiang.dongj/AAA
                 dongjiang.dongj:password@127.0.0.1:/home/dongjiang.dongj/AAA
                 /home/dongjiang.dongj/AAA
                 ../../tools/AAA
    * @param des string. like src , must dir
    * @return array($status, Array($output))
    * @author dongjiang.dongj
    * @warn 可能会出现ExecuteFail异常
    */
    public function rcp($src, $des, $_exc = False) {
        if( stripos($src,':') !== false) {
            $_Arr = explode(":", $src);
            logging::info(implode(":",array_slice(explode(":", $src), 1)));
            $src_host = $_Arr[0];
            $src_path = implode(":", array_slice(explode(":", $src), 1));
        }
        else {
            $src_host = gethostname();
            $src_path = $src;
        }
        if (realpath(dirname($src_path)) == "") {
            $src_path = $src_path;
        }
        else {
            $src_path = realpath(dirname($src_path))."/".basename($src_path);
        }
        $_src = "ftp://".$src_host.'/'.$src_path;

        if ( stripos($des,':') !== false) {
            $_Arr = explode(":", $des);
            $des_host = $_Arr[0];
            $des_path = implode(":", array_slice(explode(":", $des), 1));
        }
        else {
            $des_host = gethostname();
            $des_path = $des;
        }
        logging::debug("des_host:".$des_host."des_path:".$des_path."src_host:".$src_host."src_path:".$src_path);

        if ( is_same_host( $src_host, $des_host ) )
            return $this->run("test -f ".$src_path." && cp ".$src_path." ".$des_path." || { mkdir -p ".$des_path."; cp -rf ".$src_path."/* ".$des_path."; }", $_exc );
        
        if (realpath($des_path) == "") {
            $des_path = $des_path;
        } else {
            $des_path = realpath($des_path);
        }

        try {
            list($status, $output) = $this->run("lftp -c 'mirror ".$_src." ".$des_path."'", $_exc );
            if ($status != 0) {
                list($status, $output) = $this->run( "lftp -c 'get ".$_src." -o ".$des_path."'", $_exc );
            }
        }
        catch(ExecuteFail $e) {
            list($status, $output) = $this->run( "lftp -c 'get ".$_src." -o ".$des_path."'", $_exc );
        }
        
        if ($status != 0) {
            logging::warn("ftp not valid for rcp from ".$src." to ".$des.", try scp" );
            $this->run( "scp -r -o StrictHostKeyChecking=no -o PasswordAuthentication=no ".$src_host.":".$src_path." ".$des_path, $_exc );
        }
        return Array($status, $output);
    }

    /**
    * run : 实现run 并且 建立机器之间的信任关系.
    * @param cmd string. 输入需要执行的shell cmd 
    * @return array($status, Array($output))
    * @author dongjiang.dongj
    * @warn 可能会出现ExecuteFail异常
    */
    public function run($cmd, $_exc = False) {
        if ( is_local( $this->gethost() )) {
            return $this->execute( $cmd, $_exc );
        }
        else {
            if ( ! is_authenticate( $this->gethost() )){
                build_authenticate( $this->gethost() );
            }
            return $this->execute( "ssh -o StrictHostKeyChecking=no -o PasswordAuthentication=no \"".$this->gethost()."\" \"".$cmd."\"", $_exc);
        }
    }

    /** 析构方法 */
    public function __destruct(){
        parent::__destruct();
    }
}

?>
