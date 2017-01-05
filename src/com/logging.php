<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/Object.php");
require_once(dirname(__FILE__)."/util.php");
if (!class_exists('LOGTYPE'))
{
    class LOGTYPE {
        const info = 8;
        const INFO = 8;
        const debug = 16;
        const DEBUG = 16;
        const warn = 4;
        const WARN = 4;
        const error = 1;
        const ERROR = 1;
        const pause = 2;
        const PAUSE = 2;
    }
}

if (!class_exists('logging')) {
    /**
    * 基础日志类
    *   实现基本日志方法. 包括日志等级，日志格式，日志输出模式
    *   日志等级包括：
    *       INFO、DEBUG、WARN、ERROR与FATAL
    *   日志格式：
    *       如：
    *           [2014-11-30 16:11:02,67][ERROR][loggingTest.php:logging:getLogger:258]: XXXXX
    *   日志输出模式:
    *       正常stdout或者file输出
    *   
    *   可实现静态 或 对象的调用
    * @author dongjiang.dongj
    */
    class logging extends Object {
        static private $LEVELTYPE = 31;
        static private $COLORS = Array('WARNING'=>33, 'INFO'=>36, 'DEBUG'=>32, 'CRITICAL'=>35, 'ERROR'=>31);
        static private $_fp = Null; //全局一个fp
        /**
        * 实现构造方法
        * @author dongjiang.dongj
        */
        function __construct() {
            parent::__construct();
            list($BLACK, $RED, $GREEN, $YELLOW, $BLUE, $MAGENTA, $CYAN, $WHITE) = range(30,37);
            self::$LEVELTYPE = LOGTYPE::info | LOGTYPE::warn | LOGTYPE::debug | LOGTYPE::error | LOGTYPE::pause;
            self::$COLORS = Array('WARNING'=>$YELLOW, 'INFO'=>$CYAN, 'DEBUG'=>$GREEN, 'CRITICAL'=>$MAGENTA, 'ERROR'=>$RED);
            updated_data_TS(); // 上传TS
        }
    
        /**
        * 重载系统方法__get
        * @author dongjiang.dongj
        */
        function __get($property_name) {
            $this->info("class ".__CLASS__." : get private attribute $property_name");
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
            $this->info("class ".__CLASS__." : set private attribute $property_name");
            $this->$property_name = $value;
        }

        /**
        * private 方法. 处理Backtrace
        * @author dongjiang.dongj
        */
        private static function _getBacktrace($Arr) {
            $retA = Array();
            if (!is_array($Arr)){
                throw new Exception("$Arr is not Array, please check!");
            }
            if ( is_array($Arr) && count($Arr) >= 2 ) {
                if (isset($Arr[1]['file'])) {
                    array_push($retA, $Arr[1]['file']);
                }
                else {
                    array_push($retA, "Null");
                }
                
                if (isset($Arr[1]['class'])) {
                    array_push($retA, $Arr[1]['class']);
                }
                else {
                    array_push($retA, "Null");
                }

                if (isset($Arr[1]['function'])) {
                    array_push($retA, $Arr[1]['function']);
                }
                else {
                    array_push($retA, "Null");
                }
                    
                if (isset($Arr[0]['line'])) {
                    array_push($retA, $Arr[0]['line']);
                }
                else {
                    array_push($retA, "Null");
                }
            }
            else if (is_array($Arr) && count($Arr) == 1) {
                if (isset($Arr[0]['file'])) {
                    array_push($retA, $Arr[0]['file']);
                }
                else {
                    array_push($retA, "Null");
                }
                
                if (isset($Arr[0]['class'])) {
                    array_push($retA, $Arr[0]['class']);
                }
                else {
                    array_push($retA, "Null");
                }

                if (isset($Arr[0]['function'])) {
                    array_push($retA, $Arr[0]['function']);
                }
                else {
                    array_push($retA, "Null");
                }
                    
                if (isset($Arr[0]['line'])) {
                    array_push($retA, $Arr[0]['line']);
                }
                else {
                    array_push($retA, "Null");
                }
            }
            else {
                array_push($retA, "Null", "Null", "Null", "Null");
            }
            return $retA;
        }
        
        /**
        * static 方法 info日志
        *    使用：logging::info('aaa', array(), null, True, $aaa);
        *        或者  $log = new logging();
        *              $log->info('aaa', array(), null, True, $aaa);
        * @parma 支持魔法传参. 
        * @return bool
        * @author dongjiang.dongj
        */
        public static function info(){
            $levelname = "\033[1;".self::$COLORS["INFO"]."m"."INFO"."\033[0m";
            $_bt = debug_backtrace();
            $retA = logging::_getBacktrace($_bt);
            $fmt = "[".date("Y-m-d H:i:s,".floor(microtime()*1000))."][".$levelname."][".basename($retA[0]).":".$retA[1].":".$retA[2].":".$retA[3]."]: ";
            $msgs = func_get_args();
            $ret = True;
            if ( self::$LEVELTYPE & LOGTYPE::INFO ) {
                if (is_resource(self::$_fp)) {
                    $_tmp = print_r($fmt, True);
                    foreach($msgs as $k=>$msg)
                        $_tmp = $_tmp.print_r($msg, True).print_r(" ", True);
                    $_tmp = $_tmp."\n";
                    $ret = fwrite(self::$_fp, $_tmp);
                }
                $ret = print_r($fmt);
                foreach($msgs as $k=>$msg)
                    $ret = $ret && print_r($msg) && print_r(" ");
                $ret = $ret && print_r("\n");
            }
            return $ret;
        }

        /**
        * static 方法 debug日志
        *    使用：logging::debug('aaa', array(), null, True, $aaa);
        *        或者  $log = new logging();
        *              $log->debug('aaa', array(), null, True, $aaa);
        * @parma 支持魔法传参. 
        * @return bool
        * @author dongjiang.dongj
        */
        public static function debug(){
            $levelname = "\033[1;".self::$COLORS["DEBUG"]."m"."DEBUG"."\033[0m";
            $_bt = debug_backtrace();
            $retA = logging::_getBacktrace($_bt);
            $fmt = "[".date("Y-m-d H:i:s,".floor(microtime()*1000))."][".$levelname."][".basename($retA[0]).":".$retA[1].":".$retA[2].":".$retA[3]."]: ";
            $msgs = func_get_args();
            $ret = True;
            if ( self::$LEVELTYPE & LOGTYPE::DEBUG ) {
                if (is_resource(self::$_fp)) {
                    $_tmp = print_r($fmt, TRUE);
                    foreach($msgs as $k=>$msg)
                        $_tmp = $_tmp.print_r($msg, True).print_r(" ", true);
                    $ret = fwrite(self::$_fp, $_tmp."\n");
                }
                $ret = print_r($fmt);
                foreach($msgs as $k=>$msg)
                    $ret = $ret && print_r($msg) && print_r(" ");
                $ret = $ret && print_r("\n");
            }
            return $ret;
        }
        
        /**
        * static 方法 warn日志
        *    使用：logging::warn('aaa', array(), null, True, $aaa);
        *        或者  $log = new logging();
        *              $log->warn('aaa', array(), null, True, $aaa);
        * @parma 支持魔法传参. 
        * @return bool
        * @author dongjiang.dongj
        */
        public static function warn(){
            $levelname = "\033[1;".self::$COLORS["WARNING"]."m"."WARNING"."\033[0m";
            $_bt = debug_backtrace();
            $retA = logging::_getBacktrace($_bt);
            $fmt = "[".date("Y-m-d H:i:s,".floor(microtime()*1000))."][".$levelname."][".basename($retA[0]).":".$retA[1].":".$retA[2].":".$retA[3]."]: ";
            $msgs = func_get_args();
            $ret = True;
            if ( self::$LEVELTYPE & LOGTYPE::WARN ) {
                if (is_resource(self::$_fp)) {
                    $_tmp = print_r($fmt, TRUE);
                    foreach($msgs as $k=>$msg)
                        $_tmp = $_tmp.print_r($msg, True).print_r(" ", True);
                    $ret = fwrite(self::$_fp, $_tmp."\n");
                }
                $ret = print_r($fmt);
                foreach($msgs as $k=>$msg)
                    $ret = $ret && print_r($msg) && print_r(" ");
                $ret = $ret && print_r("\n");
            }
            return $ret;
        }
        
        /**
        * static 方法 error日志
        *    使用：logging::error('aaa', array(), null, True, $aaa);
        *        或者  $log = new logging();
        *              $log->error('aaa', array(), null, True, $aaa);
        * @parma 支持魔法传参. 
        * @return bool
        * @author dongjiang.dongj
        */
        public static function error(){
            $levelname = "\033[1;".self::$COLORS["ERROR"]."m"."ERROR"."\033[0m";
            $_bt = debug_backtrace();
            $retA = logging::_getBacktrace($_bt);
            $fmt = "[".date("Y-m-d H:i:s,".floor(microtime()*1000))."][".$levelname."][".basename($retA[0]).":".$retA[1].":".$retA[2].":".$retA[3]."]: ";
            $msgs = func_get_args();
            $ret = True;
            if ( self::$LEVELTYPE & LOGTYPE::ERROR ) {
                if (is_resource(self::$_fp)) {
                    $_tmp = print_r($fmt, TRUE);
                    foreach($msgs as $k=>$msg)
                        $_tmp = $_tmp.print_r($msg, True).print_r(" ", True);
                    $ret = fwrite(self::$_fp, $_tmp."\n");
                }
                $ret = print_r($fmt);
                foreach($msgs as $k=>$msg)
                    $ret = $ret && print_r($msg) && print_r(" ");
                $ret = $ret && print_r("\n");
            }
                
            return $ret;
        }

        /**
        * static 方法 pause日志
        *    使用：logging::pause('aaa', array(), null, True, $aaa);
        *        或者  $log = new logging();
        *              $log->pause('aaa', array(), null, True, $aaa);
        * @parma 支持魔法传参. 
        * @return bool
        * @author dongjiang.dongj
        */
        public static function pause(){
            $levelname = "\033[1;".self::$COLORS["CRITICAL"]."m"."CRITICAL"."\033[0m";
            $_bt = debug_backtrace();
            $retA = logging::_getBacktrace($_bt);
            $fmt = "[".date("Y-m-d H:i:s,".floor(microtime()*1000))."][".$levelname."][".basename($retA[0]).":".$retA[1].":".$retA[2].":".$retA[3]."]: ";
            $msgs = func_get_args();

            $ret = True;
            if ( self::$LEVELTYPE & LOGTYPE::PAUSE ) {
                if (is_resource(self::$_fp)) {
                    $_tmp = print_r($fmt, TRUE);
                    foreach($msgs as $k=>$msg)
                        $_tmp = $_tmp.print_r($msg, True).print_r(" ", True);
                    $ret = fwrite(self::$_fp, $_tmp."\n");
                }
                $ret = print_r($fmt);
                foreach($msgs as $k=>$msg)
                    $ret = $ret && print_r($msg) && print_r(" ");
                $ret = $ret && print_r("\n");
            }
            fwrite(STDOUT,"\n Enter a 'Enter' to continie!");
            $_count = 0;
            while(fgets(STDIN) != "\n" && $_count <= 3) {
                fwrite(STDOUT,"\n Enter one 'Enter' to continie!");
                $_count = $_count + 1;
            }
            return $ret;

        }

        /**
        * static 方法. 设置日志等级. 设置的等级在同一个进程中生效.
        *    使用：logging::setLevel(31);
        *        或者  $log = new logging();
        *              $log->setLevel(31);
        *
        * 参数说明 LOGTYPE中等级各个等级的值.
        *      当设置的$Level 为 31 表示按位与，符合的表示位为真. ($Level & $Level - 1) == 0, 将设置等级，如果不为真，不设置.
        * @parma $Level int. 
        * @return bool
        * @author dongjiang.dongj
        */
        static public function setLevel($Level){
            if ( ($Level & $Level - 1) == 0 &&  ($Level <=( LOGTYPE::DEBUG*2 -1)) && ($Level > 0) ) {
                self::$LEVELTYPE = $Level*2 - 1;
                return True;
            }
            else {
                logging::error($Level, ' is not in '.self::$LEVELTYPE.'.');
                //throw new Exception('Set logLevel is error! You must use class'.LOGTYPE);
                return False;
            }
        }

        /**
        * static 方法. 获得当前日志等级.
        * @return LOGTYPE.
        * @author dongjiang.dongj
        */
        public static function getLevelType() {
            return (self::$LEVELTYPE+1)/2;
        }
        
        /**
        * static 方法. 获得写日志文件handle
        * @return bool
        * @author dongjiang.dongj
        */       
        public static function getLogger($fileName) {
            if ($fileName == "" or $fileName == Null) {
                logging::error("Filename : $fileName , this is illegal! set file to stdout~\n");
                self::$_fp = "";
                return False;
            }
            
            $fp = fopen($fileName,"a+");
            if ( !is_resource($fp) ) {
                logging::error("open $fileName failed.\n Set file to stdout~ \n");
                self::$_fp = "";
            }
            else {
                if (is_resource(self::$_fp)) {
                    fclose(self::$_fp);
                }
                self::$_fp = $fp;
            }
            return is_resource(self::$_fp);
        }

        public function __destruct(){
            if (is_resource(self::$_fp)) {
                fclose(self::$_fp);
            }
            parent::__destruct();
        }
    }
}

?>
