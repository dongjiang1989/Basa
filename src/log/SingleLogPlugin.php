<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/LogIPlugin.php");
require_once(dirname(__FILE__)."/../com/file.php");

/**
* Pvlog Exception
* @author wanling.dx
*/
class LogNotProduce extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }   
    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }   
}
class SingleLogPlugin extends file implements LogIPlugin {
    const MODE = "Single";
    const CLASSNAME = __CLASS__;
    private $_pre_line;
    public function __construct($filename, $host="127.0.0.1", $init_from="", $auto_bak=False,  $is_touch = True){
        parent::__construct($filename=$filename, $host=$host, $init_from=$init_from, $auto_bak=$auto_bak, $is_touch=$is_touch);
        //TODO
        $this->_pre_line = $this->lines();
    }
    
    public function __call($f, $p) {
        if (method_exists($this, $f.sizeof($p))) {
            return call_user_func_array(array($this, $f.sizeof($p)), $p);
        } else {
            throw new CallFunctionFail("Tried to call unknown method".get_class($this).'::'.$f, RETTYPE::ERR);
        }
    }

    public function get() {
         try{
              $orilog = $this->_getRange();
         } catch(LogNotProduce $e) {
              return false;
         }
         $args = func_get_args();
         if (func_num_args() == 0 ) {
             return $orilog;
         } 
         elseif (func_num_args() == 1 ) {
             return $this->_get1($orilog,$args);
         }
         else {
             logging::error(__CLASS__ . " Args count is not supported");
             return false;
         }
    }
/**
  *private 方法 当get()传參只有一个时且为taobaolog,返回taobaolog解析后的arr
  *
  *@param   $string section名字（taobaolog）
  *@return  返回指定section
  *@author  wanling.dx
  **/
    private function _get1($orilog,$args) {
             $mode = $args[0];
             if ($mode == 'taobaolog'){
                 return $this->getQuery($orilog);
             } else {
                 logging::error(__CLASS__ . " Args is not supported,only support taobaolog");
                 return false;
              }
    }
/**
  *public 方法 获取taobaolog的query，并解析为kvarr
  *
  *@param  新产生的taobaolog
  *@return  每条taobaolog的query kvarr
  *@author wanling.dx
  **/
    public function getQuery($orilog) {
        $result = array();
        foreach($orilog as $log){
            list($date, $time, $oriquery) = split (" ", $log, 3);
            $str = parse_url($oriquery);
            $query = $str["query"];
            unset($ret);
            parse_str($query,$ret);
            $result[] = $ret;
        }
       return $result;
    }
/**
  *private 方法 获取最新产生的log
  *
  *@param  
  *@return  新产生的log
  *@author wanling.dx
  **/
    private function _getRange() {
        $num = $this->line();
        if($num == 0){
            throw new LogNotProduce("Log Did not produce", RETTYPE::ERR);
        }
        else{
            $orilog = $this->tail("-n $num"); 
            return $orilog;
        }
    }
    

/**
  *public 方法 获取新产生的log条数
  *
  *@param  
  *@return  新产生的log条数
  *@author wanling.dx
  **/
    public function line() {
        $cur_line = $this->lines(); 
        $num = $cur_line - $this->_pre_line;
        if($num < 0){
            logging::error(__CLASS__ . " The produce log num is lessthan 0,please check   ");
            return false;
        }
        else{
            return $num;
        }
    }

/**
  *public 方法 判断log中是否存在指定正则,支持一个正则，并返回
  *
  *@param  $pattern
  *@return  $array 
  *@author wanling.dx
  **/
    public function seek() {
        $result = array();
        $args = func_get_args();
        if (func_num_args() == 1 ) {
            try{
                 $orilog = $this->_getRange();
            } catch(LogNotProduce $e) {
                 return false;
            }
            $key = $args[0];
            foreach($orilog as $log){
                if(preg_match($key,$log)){
                    logging::debug(__CLASS__ . " The args $key is  exist in log");
                    $result[] = $log;   
                }   
            }
            if(empty($result)){
                logging::error(__CLASS__ . " The seek result  is empty");
                return false;
            }
            else{
                return $result;
            }
        }
        else{
            logging::error(__CLASS__ . " The args num is wrong, only support one args");
            return false;
        }
    }

/**
  *public 方法 判断log中是否存在指定内容(可以多值),并返回
  *
  *@param  $string
  *@return  $array[$args]=>$logarr 
  *@author wanling.dx
  **/
    public function search() {
        $result = array();
        $args = func_get_args();
        if (func_num_args() >= 1 ) {
            try{
            $orilog = $this->_getRange();
            } catch(LogNotProduce $e) {
                 return false;
            }
            foreach($args as $key){
                foreach($orilog as $log){
                    if(strstr($log,$key)){
                        logging::debug(__CLASS__ . " The args $key is  exist in log");
                        $result[$key][] = $log;   
                    }   
                }
            }
            if(empty($result)){
                logging::error(__CLASS__ . " The search result  is empty");
                return false;
            }
            else{
                return $result;
            }
        }
        else{
            logging::error(__CLASS__ . " The args is empty");
            return false;
        }
    }


/**
  *public 方法 判断log中是否存在指定内容，多个传参，之间为或的关系
  *
  *@param  $string
  *@return  true/false
  *@author wanling.dx
  **/
    public function isexist() {
        $args = func_get_args();
        if (func_num_args() >= 1 ) {
            $orilog =$this->_getRange();
            foreach($args as $value){
                foreach($orilog as $log){
                    if(strstr($log,$value)){
                        logging::debug(__CLASS__ . " The args $value is  exist in log");
                        return true;   
                    }   
                }
            }
            logging::error(__CLASS__ . " The args is not exist");
            return false;
        }
        else{
            logging::error(__CLASS__ . " The args is empty");
            return false;
        }
    }

/**
  *public 方法 判断log文件是否滚动
  *
  *@param  
  *@return  true/false
  *@author wanling.dx
  **/
    public function isroll() {
        $cur_line = $this->lines(); 
        $num = $cur_line - $this->_pre_line;
        if($num < 0){
            logging::error(__CLASS__ . " The produce log num is lessthan 0,please check   ");
            return false;
        }
        elseif($num == 0){
            logging::debug(__CLASS__ . " The log does not rolled");
            return false;
        }
            logging::debug(__CLASS__ . " The log  rolled");
        return true;
    }
    public function __destruct() {
        //TODO
        parent::__destruct();
    }
}
?>
