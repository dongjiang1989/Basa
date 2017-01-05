<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : http.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2015-03-24 19:13
*   Description   : Http解析 业务方法
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../../com/util.php");

/**
* Html解析业务方法
*   支持块的 分级、查找、替换
* @author dongjiang.dongj
*/
class Http extends Object {

    static private $proxy = "proxy.simba.tbsite.net:8000";
    static private $timeout = 1;  #设置一次curl超时时间

    /**
    * http构造方法
    *   实现 http的基本操作
    * @author dongjiang.dongj 
    */
    public function __construct(){
        parent::__construct();
        //TODO
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
    * 输入url, 获得请求结果
    * @param $url 输入url
    * @return $string
    * @author dongjiang.dongj
    */
    static public function get($url) {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $data = null;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); 
            curl_setopt($curl, CURLOPT_TIMEOUT, (int)self::$timeout); 

            $data = curl_exec($curl);
            if ($data === False) {
                curl_setopt($curl, CURLOPT_PROXY, self::$proxy);
                $data = curl_exec($curl);
                if ($data === False) {
                    curl_close($curl);
                    logging::error("Get PROXY data Error!");
                    return null;
                } else {
                    curl_close($curl);
                    return $data;
                }   
            } else {
                curl_close($curl);
                return $data;
            }
        } else {
            logging::error("Input url is not validate url! url:", $url);
            return null;
        }
    }

     /**
    * POST 输入url, 获得请求结果
    * @param $url 输入url
    * @return $string
    * @author dongjiang.dongj
    */
    static public function post($url) {
        $fields_string = parse_url($url, PHP_URL_QUERY);
        $tmpurl_arr = parse_url($url);

        if (isset($tmpurl_arr['host']) && isset($tmpurl_arr['path'])) {
            $tmpurl = "";

            if (isset($tmpurl_arr['port'])) {
                $tmpurl_arr['host'] = trim($tmpurl_arr['host'], "/").":".$tmpurl_arr['port'];
            }

            if (isset($tmpurl_arr['scheme'])) {
                $tmpurl = $tmpurl_arr['scheme']."://";
            } else {
                $tmpurl = "http://";
            }
            
            if (isset($tmpurl_arr['user']) && isset($tmpurl_arr['pass'])) { 
                $tmpurl = $tmpurl.$tmpurl_arr['user'].":".$tmpurl_arr['pass']."@";
            } else if (isset($tmpurl_arr['user'])) {
                $tmpurl = $tmpurl.$tmpurl_arr['user']."@";
            } else if (isset($tmpurl_arr['pass'])) {
                $tmpurl = $tmpurl.":".$tmpurl_arr['pass']."@";  
            } else {
                $tmpurl = $tmpurl;
            }
    
            $tmpurl = $tmpurl.trim($tmpurl_arr['host'], '/')."/".ltrim($tmpurl_arr['path'], "/");
            
            //logging::debug($fields_string,  $tmpurl);

            $data = null;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $tmpurl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); 

            curl_setopt($curl, CURLOPT_TIMEOUT, self::$timeout); 
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string );

            $data = curl_exec($curl);
            if ($data === False) {
                curl_setopt($curl, CURLOPT_PROXY, self::$proxy);
                $data = curl_exec($curl);
                if ($data === False) {
                    curl_close($curl);
                    logging::error("Get PROXY data Error!");
                    return null;
                } else {
                    curl_close($curl);
                    return $data;
                }   
            } else {
                curl_close($curl);
                return $data;
            }
        } else {
            logging::error("Input url is not validate url! url:", $url);
            return null;
        }
    }

    /**
    *  设置超时时间 如：2 表示两秒
    * @param int 类型
    * @return bool
    * @author dongjiang.dongj
    */
    static public function setTimeout($time) {
        if (is_int($time) && (int)$time >= 0 ) {
            self::$timeout = $time;
            return true;
        } else {
            logging::warn("Input:", $time, " is not int!!! so set to 1s.");
            self::$timeout = 1;
            return false;
        }
    }

    /**
    *  获得最大超时时间
    * @return int
    * @author dongjiang.dongj
    */
    static public function getTimeout() {
        return self::$timeout;
    }

    /**
    * 修改proxy. 如："127.0.0.1:8000";
    * @param $string. 
    * @return bool.
    * @author dongjiang.dongj
    */
    static public function setProxy($string) {
        //TODO: maybe string not valid!
        self::$proxy = $string;
        return true;
    }

    /**
    *  获得proxy string
    * @return Proxy string.
    * @author dongjiang.dongj
    */
    static public function getProxy() {
        return self::$proxy;
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
