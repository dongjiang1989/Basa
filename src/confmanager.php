<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : confmanager.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-11-25 19:13
*   Description : 
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/com/baseFactory.php");
require_once(dirname(__FILE__)."/com/logging.php");
require_once(dirname(__FILE__)."/com/util.php");

final class ConfigManager extends BaseFactory {
    /**
    *use set (mode , (classname, this->params)
    *@var Array
    */
    static private $Register_ids = Array(); //'$mode' => array("classname"=>$classname, 'params'=>$params)

    /** set object to ConfigManager */
    private $ConfObj = Array(); // '$filename' => array('$mode', '$handle')

    const CONFIGPATH = "/conf/";

    /** 构造函数 */
    public function __construct(){
        parent::__construct();
        //TODO
        self::RegisterAllPlugins();
        updated_data_TS();
    }

    /** 扩展使用的接口 */
    final public static function initialize($config) {
        return True;
    }

    /** 
    * 注册Conf类型方法
    *
    *   $ConfigManager->RegisterPlugin('conf', Array('aaa'));
    * @param  string  input classname
    * @param  array  input params ; default "". 实例化对象所需要添加的参数。
    * @return void
    * @author dongjiang.dongj
    */
    final public static function RegisterPlugin($classname, $params=""){
        if (self::check($classname)) {
            if ( in_array($classname::MODE, array_keys(self::$Register_ids)) || in_array($classname::CLASSNAME, self::_getAllRegisterInfo("classname")) )
            {
                //throw new RegisterFail("$classname had been Registed in ConfigManager: $classname \n", RETTYPE::ERR);
                logging::warn("$classname had been Registed in ConfigManager: \n", array_keys(self::$Register_ids));
            } else {
                self::$Register_ids[$classname::MODE] = array("classname"=>$classname::CLASSNAME, 'params'=>$params);
            }
        }
        else {
            throw new RegisterFail( "Class ".$classname." is not find : const MODE or CLASSNAME ! \n", RETTYPE::ERR);
        }
    }

    /**
    * private 方法，内部使用获得$Register_ids信息使用
    */
    private static function _getAllRegisterInfo($key) {
        $_tmpArr = Array();
        foreach (self::$Register_ids as $k=>$value) {
            foreach ($value as $k1=>$value) {
                if ($k1 == $key) {
                    $_tmpArr[] = $value;
                }
            }
        }
        return $_tmpArr;
    }
    
    /** 
    * 获得具体方法的handle
    *
    *   $ConfigManager->getInstanceHandle('Text', array('aaa.conf', '127.0.0.1', ...));
    * @param  string  input mode
    * @return obj. handle of Class
    * @author dongjiang.dongj
    */
    final public function getInstanceHandle($mode, $params="") {
        if (in_array($mode, self::getPluginMode())) {
            if (!empty($params)) {
                $reflect = new ReflectionClass(self::$Register_ids[$mode]['classname']);
                $eobj = $reflect->newInstanceArgs($params);
                $_basename = $eobj->basename();
                if ( in_array($_basename, $this->getConfigInstance()) ) {
                    //throw new RegisterFail($eobj->basename()." had been instanced !\n", RETTYPE::ERR);
                    logging::warn($_basename." had been instanced !\n");
                    unset($eobj);
                    return $this->ConfObj[$_basename]['handle'];
                }
                else {
                    $this->ConfObj[$_basename] = Array("mode"=>$mode, "handle"=>$eobj);
                    return $eobj;
                }
            } else {
                $reflect = new self::$Register_ids[$mode]['classname']();
                $_basename = $reflect->basename();
                if ( in_array($_basename, $this->getConfigInstance()) ) {
                    logging::warn($_basename." had been instanced !\n");
                    unset($reflect);
                    return $this->ConfObj[$_basename]['handle'];
                } else {
                    $this->ConfObj[$_basename] = Array("mode"=>$mode, "handle"=>$reflect);
                    return $reflect;
                }
            }
        }
        else {
            logging::error($mode." is not support now! please check ?! \n ", "Mode:", array_keys(self::$Register_ids));
            return Null;
        }
    }

    /**
    * 获得所有已经实例化的object
    * @return Array()
    * @author dongjiang.dongj
    */
    final public function getInstanceHandles() {
        return $this->_getAllInstanceInfo('handle');
    }

    /**
    * private函数，得所有已经实例化的信息
    * @param input $key. Must in Array('classname', 'handle')
    * @return Array()
    * @author dongjiang.dongj
    */
    private function _getAllInstanceInfo($key) {
        $_tmpArr = Array();
        foreach ($this->ConfObj as $k=>$value) {
            foreach ($value as $k1=>$value) {
                if ($k1 == $key)
                    $_tmpArr[$k] = $value;
            }
        }
        return $_tmpArr;
    }

    /**
    *删除指定注册实例
    *   手工删除指定实例化数据对象
    * @return bool
    * @author dongjiang.dongj
    */
    final public function unRegisterInstance($filename) {
        if (in_array($filename, $this->getConfigInstance())) {
            unset($this->ConfObj[$filename]);
            return !in_array($filename, $this->getConfigInstance());
        }
        else {
            logging::error("$filename is not Instance, You must Instance it?!\n");
            return True;
        }
    }

    /**
    *删除所有注册实例
    *   删除所有已经实例化数据对象
    * @return bool
    * @author dongjiang.dongj
    */
    final public function unRegisterAllInstance() {
        foreach ($this->ConfObj as $k=>$v) {
            unset($v);
            unset($this->ConfObj[$k]);
        }
        return $this->ConfObj == Array();
    }

    /** 
    * private方法，获得Register Conf Mode
    * @return Array
    * @author dongjiang.dongj
    */
    final public static function getPluginMode() {
        return array_keys(self::$Register_ids);
    }

    /** 
    * 获得Instance Conf Object
    * @return Array()
    * @author dongjiang.dongj
    */
    final public function getConfigInstance() {
        return array_keys($this->ConfObj);
    }

    /**
    * 获得Check 注册类型是否满足需求
    * @return bool
    * @author dongjiang.dongj 
    */
    final public static function check($classname) {

        if ( defined("$classname::MODE") && defined("$classname::CLASSNAME") )
        {
            return True;
        }
            
        return False;
    } 

    public function __destruct(){
        $this->unRegisterAllInstance();
        parent::__destruct();
    }

    /**
    * private function
    */
    private static function _del($var) {
        $filter_Array = Array('ConfIPlugin.php', ".", ".." , "");
        if ( in_array($var, $filter_Array) || ( count($var) > 0 && substr($var, 0, 1) == ".")) {
            return False;
        } else {
            return True;
        }
    }

    /**
    * scandir ./conf/ 已经实现的 Mode，进行注册到工厂模式中
    * @return Array. 返回所有类型
    * @author dongjiang.dongj
    */
    public static function RegisterAllPlugins() {
        $_fullpath = (realpath(dirname(__FILE__).self::CONFIGPATH)=="") ? 
                     dirname(__FILE__).self::CONFIGPATH :
                     realpath(dirname(__FILE__))."/".basename(self::CONFIGPATH);
        logging::info("Conf Plugin Path : ".$_fullpath);
        
        $RetFileArr = array_filter(scandir($_fullpath, 1), 'self::_del');
        foreach($RetFileArr as $v) {
            logging::debug(dirname(__FILE__).self::CONFIGPATH.$v);
            require_once(dirname(__FILE__).self::CONFIGPATH.$v);
            $classname = explode('.', $v);
            if (count($classname) >= 2) {
                array_pop($classname);
                self::RegisterPlugin(implode(",", $classname));
            } else {
                logging::error($v.' class is not find! please check?!');
            }
        }
        return self::getPluginMode();
    }
}

?>

