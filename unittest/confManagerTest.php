<?php
require_once(dirname(__FILE__)."/../src/confmanager.php");

class AAAConf {
    const MODE = "aaaa";
}

class BBBConf {
    const MODE = "BBBConf";
    Const CLASSNAME = __CLASS__;
    function basename() {
        return "aaa.conf";
    }
}

class TestConfmanager extends PHPUnit_Framework_TestCase {
    function test_registerPlugin () {
        ConfigManager::RegisterAllPlugins();
        $CM = new ConfigManager();
        $CM->RegisterAllPlugins();
        $this->assertTrue(in_array('text',ConfigManager::getPluginMode()));
        $this->assertTrue(in_array('yaml',ConfigManager::getPluginMode()));
        try {
            $CM->RegisterPlugin('Exception');
            $this->assertTrue(False);
        } catch (RegisterFail $e) {
            $e->__toString();
            $this->assertEquals($e->getcode(), -1);
        }
        $this->assertTrue(!in_array('Exception', $CM->getPluginMode()));
        unset($CM);
        var_dump(ConfigManager::getPluginMode());
        $this->assertTrue(in_array('text',ConfigManager::getPluginMode()));
        $this->assertTrue(in_array('yaml',ConfigManager::getPluginMode()));
    }


    function test_registerPlugin2 () {
        var_dump(ConfigManager::getPluginMode());
        $CM = new ConfigManager();
        try {
            ConfigManager::RegisterPlugin('AAAConf');
            $this->assertTrue(False);
        } catch (RegisterFail $e) {
            $e->__toString();       
            $this->assertEquals($e->getcode(), -1);
        }
        
        try {
            ConfigManager::RegisterPlugin('aaaaaaaaaaaaaaaaaaaa');
            $this->assertTrue(False);
        } catch (RegisterFail $e) {
            $e->__toString();       
            $this->assertEquals($e->getcode(), -1);
        }
    }


    function test_initialize() {
        $this->assertTrue(ConfigManager::initialize('aa'));
    }

    function test_getInstanceHandle() {
        $CM = new ConfigManager();
        $CM->RegisterAllPlugins();

        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/data/test.conf"));
        $this->assertEquals(gettype($Obj), "object");
        $this->assertEquals($Obj->basename(), "test.conf"); 

        $this->assertEquals(count($CM->getConfigInstance()), 1);

        $Obj1 = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/data/AAAA.conf"));

        $this->assertEquals(gettype($Obj1), "object");
        $this->assertEquals($Obj1->basename(), "AAAA.conf");
        $this->assertEquals(count($CM->getConfigInstance()), 2);
        $this->assertEquals(gettype($CM->getInstanceHandle("text", array(dirname(__FILE__)."/data/test.conf"))), 'object');

        $this->assertEquals(count($CM->getConfigInstance()), 2);

        $CM->unRegisterAllInstance();
        $this->assertEquals(count($CM->getConfigInstance()), 0);
        
        $CM->unRegisterAllInstance();
        $this->assertEquals(count($CM->getConfigInstance()), 0);
    }

    function test_unRegisterInstance() {
        $CM = new ConfigManager();
        $CM->RegisterAllPlugins();

        $Obj1 = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/data/AAAA.conf"));        

        $this->assertTrue(in_array('AAAA.conf',$CM->getConfigInstance())); 

        $ret = $CM->unRegisterInstance('AAAA.conf');
        $this->assertTrue(!in_array('',$CM->getConfigInstance()));     
        $this->assertTrue($ret);

        $ret = $CM->unRegisterInstance('aaaaa'); 
        $this->assertTrue(!in_array('yaml',$CM->getConfigInstance()));
        $this->assertTrue($ret); 

    }

    function test_getInstanceHandles() {
        $CM = new ConfigManager();
        $CM->RegisterAllPlugins();

        $Obj1 = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/data/AAAA.conf"));
        $Obj2 = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/data/AAAA.conf"));

        $this->assertEquals($Obj2, $Obj1);
        $this->assertEquals(count($CM->getInstanceHandles()), 1);
        $ret = $CM->getInstanceHandles();
        var_dump($ret );
        $this->assertEquals(gettype($ret['AAAA.conf']), 'object');
    }

    function test_getInstanceHandle1() {
        $CM = new ConfigManager();
        $CM->RegisterAllPlugins();
        
        ConfigManager::RegisterAllPlugins();

        ConfigManager::RegisterPlugin('BBBConf');

        $this->assertTrue(in_array('BBBConf',ConfigManager::getPluginMode()));
        $Obj = $CM->getInstanceHandle("BBBConf");
        
        $Obj1 = $CM->getInstanceHandle("BBBConf");

        $this->assertEquals($Obj, $Obj1);
        $this->assertEquals(count($CM->getInstanceHandles()), 1);

        $ret = $CM->getInstanceHandles();
        $this->assertEquals(gettype($ret['aaa.conf']), 'object');        

        $Obj2 = $CM->getInstanceHandle("dddd");

        $this->assertEquals($Obj2, null);
    }

    function test_check() {
        $this->assertTrue(ConfigManager::check("BBBConf"));
        $this->assertFalse(ConfigManager::check("AAAConf"));
    }

}

?>
