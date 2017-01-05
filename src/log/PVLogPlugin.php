<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/LogIPlugin.php");
require_once(dirname(__FILE__)."/../com/file.php");

/**
* Pvlog Exception
* @author wanling.dx
*/
class PvlogNotProduce extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }   
    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }   
}
class PVLogPlugin extends file implements LogIPlugin {
    const MODE = "PVLog";
    const CLASSNAME = __CLASS__;
    private $PARSEARR = "\001,\002,\003,\004,\005,\006,\007,\008,\009,\010,\011,\012,\013,\014,\015,\016,\017,\018,\019,\020,\021,\022,\023,\024,\025,\026";
    private $_pre_line;
    public function __construct($filename, $host="127.0.0.1", $init_from="", $auto_bak=False, $is_touch = True){
        parent::__construct($filename=$filename, $host=$host, $init_from=$init_from, $auto_bak=$auto_bak, $is_touch=$is_touch);
        //TODO
        $this->_pre_line = $this->lines();
    }

    /**
    * 设置pv日志的分隔符. 默认使用^A, ^B ..... 分隔
    * @param $string string. 设置pv日志中的不同层级分隔符, 以逗号分隔. 
    *   如果$string == "" 或者 $string type 不是string时, ParseSep被设置为"^A,^B,^C...^Z"
    *      例如: ":, ,aa,^A" 
    *   
    * @return bool
    * @author dongjiang.dongj
    */
    public function setParseSep($string = "") {
        if ($string == "") {
            $this->PARSEARR = "\001,\002,\003,\004,\005,\006,\007,\008,\009,\010,\011,\012,\013,\014,\015,\016,\017,\018,\019,\020,\021,\022,\023,\024,\025,\026";
            logging::warn($string, "is null. so set PARSEARR to default: ", $this->PARSEARR);
            return True;
        } 
        if (gettype($string) == 'string'){
            $this->PARSEARR = $string;
            return True;
        } else {
            $this->PARSEARR = "\001,\002,\003,\004,\005,\006,\007,\008,\009,\010,\011,\012,\013,\014,\015,\016,\017,\018,\019,\020,\021,\022,\023,\024,\025,\026";
            logging::error($string, "is not string type. Set PARSEARR to default:", $this->PARSEARR);
            return False;
        }
    }    

    /**
    * 获得当前文件的分隔符
    * @return string
    * @author dongjiang.dongj
    */
    public function getParseSep() {
        return $this->PARSEARR;
    }

    public function __call($f, $p) {
        if (method_exists($this, $f.sizeof($p))) {
            return call_user_func_array(array($this, $f.sizeof($p)), $p);
        } else {
            throw new CallFunctionFail("Tried to call unknown method".get_class($this).'::'.$f, RETTYPE::ERR);
        }
    }

/**
  *public 方法 根据传参的不同，调用不同的模式，返回结果
  *1）不传参 返回全部的pvlog
  *2）传参个数为1，且为（qr/query/ads）,返回对应section
  *3）传参大于1，且第一个为value，后面为需要的数组下标，返回具体结果
  *@param 魔法传参，支持多个$string   
  *@return  返回不同模式的结果
  *@author  wanling.dx
  **/
    public function get() {
         try{
              $oripvlog = $this->_getRange();
         } catch(PvlogNotProduce $e) {
              return false;
         }
         $pvlog_arr =  $this->_parsePv($oripvlog);
         $args = func_get_args();
         if (func_num_args() == 0 ) {
             return $pvlog_arr;
         } 
         elseif (func_num_args() == 1 ) {
             return $this->_get1($pvlog_arr,$args);
         }
         else {
             return $this->_get2($pvlog_arr,$args);
         }
     }
     
/**
  *private 方法 当get()传參只有一个时，返回pvlog的qr/query/ads section,只支持这3种
  *
  *@param   $string section名字（qr/query/ads）
  *@return  返回指定section
  *@author  wanling.dx
  **/
    private function _get1($pvlog_arr,$args) {
             $mode = $args[0];
             if ($mode == 'qr'){
                 return $this->getQr($pvlog_arr);
             } elseif ($mode == 'query'){
                 return $this->getQuery($pvlog_arr);
             } elseif ($mode == 'ads'){
                 return $this->getAds($pvlog_arr);
             } else {
                 logging::error(__CLASS__ . " Args is not supported,only support qr and query and ads");
                 return false;
              }
    }
/**
  *private 方法 当get()传參>1，第一个参数表示模式，只支持value，后面的参数指定pvlog的下标值
  *
  *@param   $pvlogarr $string example：（$pvlog,value,1,2,3）
  *@return  返回pvlog指定下标值 $logarr[1][2][3]
  *@author  wanling.dx
  **/
    private function _get2($pvlog_arr,$args) {
             $mode = array_shift($args);
             if ($mode !== 'value'){
                 logging::error(__CLASS__ . " The first args is not supported,only support value");
                 return false;
             } else{
                 return $this->getValue($pvlog_arr,$args);
        }
    }
    
/**
  *public 方法 返回pvlog的指定下标的值
  *
  *@param   $pvlogarr $string example：（$pvlog,1,2,3）
  *@return  返回pvlog指定下标值 $logarr[1][2][3]
  *@author wanling.dx
  **/
    public function getValue($pvlog_arr,$keys) {
        $result = array();
        $num = count($keys);
        foreach($pvlog_arr as $pvlog){
            $tmp = $pvlog;
            foreach($keys as $key){
                if(isset($tmp[$key])){
                    $tmp = $tmp[$key];
                }
                else{
                    logging::error(__CLASS__ . " The value is not exist");
                    return false;
                }
            }
            $result[]=$tmp;
        }
       return $result;
        
    }
/**
  *private 方法 获取最新产生的pvlog
  *
  *@param  
  *@return  新产生的pvlog
  *@author wanling.dx
  **/
    private function _getRange() {
        $num = $this->line();
        if($num == 0){
            throw new PvlogNotProduce("PVlog Did not produce", RETTYPE::ERR);
        }
        else{
            $oripvlog = $this->tail("-n $num"); 
            return $oripvlog;
        }
    }
    
/**
  *public 方法 获取最新产生的每条pvlog的query串，并解析为kvarr
  *
  *@param  新产生的pvlog
  *@return  每条pvlog的query kvarr
  *@author wanling.dx
  **/
    public function getQuery($pvlog_arr) {
        $result = array();
        foreach($pvlog_arr as $pvlog){
            $oriquery = $pvlog[5][2];
            $str = parse_url($oriquery);
            $query = $str["query"];
            unset($ret);
            parse_str($query,$ret);
            $result[] = $ret;
        }
       return $result;
    }
/**
  *public 方法 获取最新产生的每条pvlog的qr结果
  *
  *@param  新产生的pvlog
  *@return  每条pvlog的qr结果
  *@author wanling.dx
  **/
    public function getQr($pvlog_arr) {
        $result = array();
        foreach($pvlog_arr as $pvlog){
            $result[] = $pvlog[5][3];
        }
       return $result;
    }
/**
  *public 方法 获取最新产生的每条pvlog的ads结果
  *
  *@param  新产生的pvlog
  *@return  每条pvlog的ads结果
  *@author wanling.dx
  **/
    public function getAds($pvlog_arr) {
        $result = array();
        foreach($pvlog_arr as $pvlog){
            $result[] = $pvlog[6];
        }
       return $result;
    }
/**
  *private 方法，解析pvlog_str转换为array，可以有多条
  *
  *@param $arr pvlogarr
  *@return  解析结果
  *@author wanling.dx
  **/
    private function _parsePv($arr){
        $ret = array();
        foreach($arr as $logarr){
            $logarr = trim($logarr, PHP_EOL); 
            $ret[] = $this->_explode($logarr);
        }
        return $ret; 
    } 
    public function parsePv($arr){
        return $this->_parsePv($arr);
    }
  /**
  *private 方法,按分隔符将str切分为多维数组
  *
  *@param $str
  *@return  切分后的数组
  *@author wanling.dx
  **/
    private function _explode($str,$i=0){
       $sep = explode(",", $this->PARSEARR);
       $num = count($sep);
       $begin = $sep[0];
       $end = $sep[$num-1];
       $cur = $sep[$i];
       $arr = explode("$cur",$str);
       foreach($arr as $item){
               if(preg_match("/[$begin-$end]/",$item) !== 0 && $i < $num-1){
                       $ret[] = $this->_explode($item,$i+1);
               }
               else{
                       $ret[] = $item;
               }
       }
       return $ret;

    }
/**
  *public 方法 获取新产生的pvlog条数
  *
  *@param  
  *@return  新产生的pvlog条数
  *@author wanling.dx
  **/
    public function line() {
        $cur_line = $this->lines(); 
        $num = $cur_line - $this->_pre_line;
        if($num < 0){
            logging::error(__CLASS__ . " The produce pv num is lessthan 0,please check   ");
            return false;
        }
        else{
            return $num;
        }
    }

/**
  *public 方法 判断pvlog中是否存在指定正则,支持一个正则，并返回匹配到的解析后的pvlogarr
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
                 $oripvlog = $this->_getRange();
            } catch(PvlogNotProduce $e) {
                 return false;
            }
            $key = $args[0];
            foreach($oripvlog as $pvlog){
                if(preg_match($key,$pvlog)){
                    logging::debug(__CLASS__ . " The args $key is  exist in pvlog");
                    $result[] = $this->_explode($pvlog);   
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
  *public 方法 判断pvlog中是否存在指定内容(可以多值),并返回匹配到的解析后的pvlogarr
  *
  *@param  $string
  *@return  $array[$args]=>$pvlogarr 
  *@author wanling.dx
  **/
    public function search() {
        $result = array();
        $args = func_get_args();
        if (func_num_args() >= 1 ) {
            try{
            $oripvlog = $this->_getRange();
            } catch(PvlogNotProduce $e) {
                 return false;
            }
            foreach($args as $key){
                foreach($oripvlog as $pvlog){
                    if(strstr($pvlog,$key)){
                        logging::debug(__CLASS__ . " The args $key is  exist in pvlog");
                        $result[$key][] = $this->_explode($pvlog);   
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
  *public 方法 判断pvlog中是否存在指定内容，多个传参，之间为或的关系
  *
  *@param  $string
  *@return  true/false
  *@author wanling.dx
  **/
    public function isexist() {
        $args = func_get_args();
        if (func_num_args() >= 1 ) {
            $oripvlog =$this->_getRange();
            foreach($args as $value){
                foreach($oripvlog as $pvlog){
                    if(strstr($pvlog,$value)){
                        logging::debug(__CLASS__ . " The args $value is  exist in pvlog");
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
  *public 方法 判断pvlog文件是否滚动
  *
  *@param  
  *@return  true/false
  *@author wanling.dx
  **/
    public function isroll() {
        $cur_line = $this->lines(); 
        $num = $cur_line - $this->_pre_line;
        if($num < 0){
            logging::error(__CLASS__ . " The produce pv num is lessthan 0,please check   ");
            return false;
        }
        elseif($num == 0){
            logging::debug(__CLASS__ . " The pvlog does not rolled");
            return false;
        }
            logging::debug(__CLASS__ . " The pvlog  rolled");
        return true;
    }

    public function __destruct() {
        //TODO
        parent::__destruct();
    }
}
?>
