<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : xml.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2015-1-5 14:13
*   Description   : xml解析-业务方法
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../../com/util.php");
require_once(dirname(__FILE__)."/../../com/IPlugin.php");
/**
* xml解析业务方法
*   支持块的 分级、查找、替换
* @author dongjiang.dongj
*/
class xml extends Object {

    const ENCODING = "GBK";

    /**
    * xml构造方法
    *   实现 xml的基本操作
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
    *  xml数据转换成array数据
    * @param String, string type
    * @return array
    * @author dongjiang.dongj
    */
    static public function xml2array($string){
        if (is_file($string)) {
            return xml::xml2arrayFromFile($string);
        } else if (filter_var($string, FILTER_VALIDATE_URL)) {
            return xml::xml2arrayFromUrl($string);
        } else {
            return xml::xml2arrayFromString($string);
        }
    }

    static public function xml2arrayFromString($string) {
        $ret = null;
        //logging::debug(mb_check_encoding($string, "GBK"));
        $_tmp = simplexml_load_string(mb_convert_encoding($string, xml::ENCODING), "SimpleXMLElement", LIBXML_NOCDATA);
        if ($_tmp === FALSE) {
            logging::error("simplexml_load_string parse xml string error!");
            throw new TypeError("simplexml_load_string parse xml string error!", RETTYPE::ERR);
        }
        $ret = json_decode(json_encode((array)$_tmp), true);
        return xml::_array_iconv("UTF-8", xml::ENCODING, $ret);
    }

    static private function _array_iconv($in_charset, $out_charset, $arr) {    
        return eval('return '.iconv($in_charset, $out_charset, var_export($arr,true).';'));    
    }

    static public function xml2arrayFromFile($file) {
        $ret = null;
        try {
            $_tmp = simplexml_load_file($file, "SimpleXMLElement", LIBXML_NOCDATA);
            if ($_tmp === FALSE) {
                logging::error("simplexml_load_file parse xml file error!");
                throw new TypeError("simplexml_load_file parse xml file error!", RETTYPE::ERR);
            }
        } catch (Exception $e) {
            logging::error("simplexml_load_file parse xml file error!");
            throw new TypeError("simplexml_load_file parse xml file error!", RETTYPE::ERR);
        }
        $ret = json_decode(json_encode((array)$_tmp), true);
        return xml::_array_iconv("UTF-8", xml::ENCODING, $ret);
    }

    static public function xml2arrayFromUrl($url) {
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
                return xml::xml2arrayFromString($data);
            }
        } else {
            curl_close($curl);
            return xml::xml2arrayFromString($data);
        }
    }

    static public function array2xml($array, $root="root") {
        if (is_array($array)) {
            $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><{$root}></{$root}>"); 
            
            $f = create_function('$f,$c,$a',' 
                    foreach($a as $k=>$v) { 
                        if(is_array($v)) {
                            if (array_keys($v) == range(0, count($v)-1)) {
                                foreach($v as $_v) {
                                    if(is_array($_v)) {
                                        $ch = $c->addChild($k);
                                        $f($f,$ch,$_v);
                                    } else {
                                        $c->addChild($k, $_v); 
                                    }
                                }
                            } else {
                                $ch = $c->addChild($k); 
                                $f($f,$ch,$v); 
                            }
                        } else { 
                            $c->addChild($k,$v); 
                        } 
                    }'); 
            $f($f,$xml,$array); 
            return $xml->asXML(); 
        } else {
            logging::error("Import data type is not array!!! type:", gettype($array));
            return null;
        }
    }

    /**
    *  获得xml获得有意义的数据
    * @return array. 返回 extensions 数据
    * @author dongjiang.dongj
    */
    static public function getAds($string){
        $ret = array();
        $tmp_ret = xml::xml2array($string);
        if ($tmp_ret['VERSION'] == -1) {
            logging::warn("Xml data VERSION is -1!");
            return array();
        } else {
            if (array_key_exists('PRESULTS', $tmp_ret) && 
                is_array($tmp_ret['PRESULTS']) && 
                array_key_exists('PRESULT', $tmp_ret['PRESULTS'])) 
            {
                $ret['PRESULTS'] = $tmp_ret['PRESULTS']['PRESULT'];
            }
            if (array_key_exists('PRESULTS_D', $tmp_ret) && 
                is_array($tmp_ret['PRESULTS_D']) &&
                array_key_exists('PRESULT', $tmp_ret['PRESULTS_D']))
            {
                $ret['PRESULTS_D'] = $tmp_ret['PRESULTS_D']['PRESULT'];
            }
            return $ret;
        }
        return array();
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
