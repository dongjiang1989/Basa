<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : html.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2015-1-2 19:13
*   Description   : Html解析 业务方法
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../../com/util.php");
require_once(dirname(__FILE__)."/../../tools/htmllib/simple_html_dom.php");
require_once(dirname(__FILE__)."/../../tools/htmllib/class.htmlParser.php");

/**
* Html解析业务方法
*   支持块的 分级、查找、替换
* @author dongjiang.dongj
*/
class Html extends Object {

    static $ENCODING = "GBK";

    /**
    * html构造方法
    *   实现 html的基本操作
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
    *  html2array function
    * @return array 
    * @author dongjiang.dongj
    */
    static public function html2array($string) {
        $data = null;
        if (is_file($string)) {
            $data = file_get_contents($string);
        } else if (filter_var($string, FILTER_VALIDATE_URL)) {
            $data = Html::_getUrl($string);
        } else {
            $data = $string;
        } 
        if ($data !== null) {
            $obj = new htmlParser($data);
            return $obj->toArray();
        } else {
            logging::error("Get Html data Error!");
            return null;
        }
    }

    static private function _getUrl($url) {
        $data = null;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($curl, CURLOPT_TIMEOUT, 1); 

        $data = curl_exec($curl);
        if ($data === False) {
            curl_setopt($curl, CURLOPT_PROXY, "proxy.simba.tbsite.net:8000");
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
    }

    /**
    *  html2obj function
    * @return obj 
    * @author dongjiang.dongj
    */
    static public function html2obj($string) {
        if (is_file($string)) {
            return Html::html2objFromFile($string);
        } else if (filter_var($string, FILTER_VALIDATE_URL)) {
            return Html::html2objFromUrl($string);
        } else {
            return Html::html2objFromString($string);
        }
    }

    static private function html2objFromString($string) {
        $obj = str_get_html(mb_convert_encoding($string, Html::$ENCODING));
        return $obj;
    }

    static private function html2objFromFile($file) {
        $obj = file_get_html($file);
        return $obj;
    }

    static private function html2objFromUrl($url) {
        $data = Html::_getUrl($url);
        if ($data === False) {
            return null;
        } else {
            return Html::html2objFromString($data);
        }
    }

    /**
    *  获得html 中 table的数据
    * @return array
    * @author dongjiang.dongj
    */
    static public function getTable($string) {
        $ret = array();
        $data = Html::html2obj($string);
        if (gettype($data) == 'object') {
            $tag_table = $data->find('table');
            foreach($tag_table as $obj) {
                $tmp_ret = array();
                $_keys = array();
                $_value = array();
                foreach($obj->find('tr') as $inobj) {
                    $tmp_ret_innert = array();
                    $_th = $inobj->find('th');
                    $_td = $inobj->find('td');
                    if (count($_th) == count($_td)) {
                        for($_i=0; $_i<count($_td); $_i++) {
                            $tmp_ret_innert[$_th[$_i]->plaintext] = $_td[$_i]->plaintext;
                        }   
                    } else if (count($_th) == 0) {
                        for($_i=0; $_i<count($_td); $_i++) {
                            $tmp_ret_innert[] = $_td[$_i]->plaintext;
                        }
                    } else if (count($_td) == 0) {
                        for($_i=0; $_i<count($_th); $_i++) {
                            $_keys[] = $_th[$_i]->plaintext;
                        }
                    } 
                    
                    if($_keys !== array() && count($_keys) == count($tmp_ret_innert)){
                        $tmp_ret[] = array_combine($_keys, $tmp_ret_innert);
                    } else if (count($tmp_ret_innert) != 0) {
                        $tmp_ret[] = $tmp_ret_innert;
                    }
                }
                $ret[] = $tmp_ret;
            }
            return $ret;
        } else {
            logging::error("Find Table in html data Error!");
            return null;
        }

    }

    /**
    *  获得html Pvlog 数据
    * @return string. 返回 pvlog 数据
    * @author dongjiang.dongj
    */
    static public function PVlog($string){
        $html = Html::html2obj($string);
        $pvlogObj = $html->find("div[id='pvlog']",0);
        if ($pvlogObj === null) {
            logging::warn("Can not find pvlog in html data!");
            return null;
        } else {
            return $pvlogObj->plaintext;
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
