<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      :YamlPlugin.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-12-12 19:13
*   Description   : 通用Yaml解析类，支持分级和缩进
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/ConfIPlugin.php");
require_once(dirname(__FILE__)."/../com/file.php");
require_once(dirname(__FILE__)."/../tools/spyc/Spyc.php");
require_once(dirname(__FILE__)."/../tools/htmllib/simple_html_dom.php");
require_once(dirname(__FILE__)."/../logmanager.php");

/**
* o=d接口解析pv日志
* 返回pvlogplugin的对象
* @author qigang.llb
*/
class HtmlPlugin extends file implements ConfIPlugin {
    const MODE = "html";
    const CLASSNAME = __CLASS__;

    private $_tmpfile = "tmp_pvlog";
    private $is_str = false;

    /**
    * html构造方法
    *   实现 html 文件load
    * @param filename: o=d的url请求串
    * @param is_str: 是否把分隔符\001替换为字符串^A
    * @param host: 文件对象所属机器,可支持远程对象
    * @param init_from: 如果不为空，使用本附件进行构建
    * @param auto_bak: 是否需要备份， default = True
    * @author qigang.llb
    */
    public function __construct($filename,$is_str=false, $host="127.0.0.1", $init_from="", $auto_bak=false){
        parent::__construct($filename=$filename, $host=$host, $init_from=$init_from, $auto_bak=$auto_bak);
        //TODO
        try {
            if(strstr($filename,"http://")){
                $html = file_get_html($filename);
            }
            else{
                $html = str_get_html($filename);
            }
            $pvlog = $html->find("div[id='pvlog']",0)->innertext;
            $fp=fopen($this->_tmpfile,"w+");
            fwrite($fp,$pvlog);
            fclose($fp);
            $this->is_str = $is_str;
        } catch( Exception $e ) {
            throw new InitFail("load url file: ".$filename." is fail! Please check yaml file ?!");
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
            throw new CallFunctionFail("Tried to call unknown method".get_class($this).'::'.$f, RETTYPE::ERR);
        }
    }



    function getPvlogObj(){
        $CM = new LogManager();
        $CM->RegisterAllPlugins();
        $PvObj = $CM->getInstanceHandle("PVLog", array($this->_tmpfile));
        if($this->is_str)
            $PvObj->setParseSep("^A,^B,^C,^D,^E,^F,^G,^H,^I,^J,^K,^L,^M,^N,^O,^P,^Q,^R,^S,^T,^U,^V,^W,^X,^Y,^Z");
        return $PvObj;
    }

    /**
    * 析构方法
    * @author dongjiang.dongj
    */
    public function __destruct() {
        //TODO
        parent::__destruct();
        $this->run("rm ".$this->_tmpfile);
    }

    public function has_key()
    {
        // TODO: Implement has_key() method.
    }

    public function iset()
    {
        // TODO: Implement iset() method.
    }

    public function isChange()
    {
        // TODO: Implement isChange() method.
    }
    public function add()
    {
        // TODO: Implement isChange() method.
    }
}
?>
