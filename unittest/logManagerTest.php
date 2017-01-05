<?php
require_once(dirname(__FILE__)."/../src/logmanager.php");

class AAALog {
    const MODE = "aaaa";
}

class BBBLog {
    const MODE = "BBBLog";
    Const CLASSNAME = __CLASS__;
    function basename() {
        return "aaa.log";
    }
}

class TestLogmanager extends PHPUnit_Framework_TestCase {
    function test_registerPlugin () {
        LogManager::RegisterAllPlugins();
        $CM = new LogManager();
        $CM->RegisterAllPlugins();
        $this->assertTrue(in_array('Mutil',LogManager::getPluginMode()));
        $this->assertTrue(in_array('Single',LogManager::getPluginMode()));
        $this->assertTrue(in_array('PVLog',LogManager::getPluginMode()));
        try {
            $CM->RegisterPlugin('Exception');
            $this->assertTrue(False);
        } catch (RegisterFail $e) {
            $e->__toString();
            $this->assertEquals($e->getcode(), -1);
        }
        $this->assertTrue(!in_array('Exception', $CM->getPluginMode()));
        unset($CM);
        var_dump(LogManager::getPluginMode());
        $this->assertTrue(in_array('Single',LogManager::getPluginMode()));
        $this->assertTrue(in_array('Mutil',LogManager::getPluginMode()));
    }


    function test_registerPlugin2 () {
        var_dump(LogManager::getPluginMode());
        $CM = new LogManager();
        try {
            LogManager::RegisterPlugin('AAALog');
            $this->assertTrue(False);
        } catch (RegisterFail $e) {
            $e->__toString();       
            $this->assertEquals($e->getcode(), -1);
        }
        
        try {
            LogManager::RegisterPlugin('aaaaaaaaaaaaaaaaaaaa');
            $this->assertTrue(False);
        } catch (RegisterFail $e) {
            $e->__toString();       
            $this->assertEquals($e->getcode(), -1);
        }
    }


    function test_initialize() {
        $this->assertTrue(LogManager::initialize('aa'));
    }

    function test_getInstanceHandle() {
        $CM = new LogManager();
        $CM->RegisterAllPlugins();

        $Obj = $CM->getInstanceHandle("Mutil", array(dirname(__FILE__)."/data/test.log"));
        $this->assertEquals(gettype($Obj), "object");
        $this->assertEquals($Obj->basename(), "test.log"); 

        $this->assertEquals(count($CM->getConfigInstance()), 1);

        $Obj1 = $CM->getInstanceHandle("Mutil", array(dirname(__FILE__)."/data/AAAA.log"));

        $this->assertEquals(gettype($Obj1), "object");
        $this->assertEquals($Obj1->basename(), "AAAA.log");
        $this->assertEquals(count($CM->getConfigInstance()), 2);
        $this->assertEquals(gettype($CM->getInstanceHandle("Mutil", array(dirname(__FILE__)."/data/test.log"))), 'object');

        $this->assertEquals(count($CM->getConfigInstance()), 2);

        $CM->unRegisterAllInstance();
        $this->assertEquals(count($CM->getConfigInstance()), 0);
        
        $CM->unRegisterAllInstance();
        $this->assertEquals(count($CM->getConfigInstance()), 0);
    }

    function test_unRegisterInstance() {
        $CM = new LogManager();
        $CM->RegisterAllPlugins();

        $Obj1 = $CM->getInstanceHandle("Mutil", array(dirname(__FILE__)."/data/AAAA.log"));        

        $this->assertTrue(in_array('AAAA.log',$CM->getConfigInstance())); 

        $ret = $CM->unRegisterInstance('AAAA.log');
        $this->assertTrue(!in_array('',$CM->getConfigInstance()));     
        $this->assertTrue($ret);

        $ret = $CM->unRegisterInstance('aaaaa'); 
        $this->assertTrue(!in_array('Single',$CM->getConfigInstance()));
        $this->assertTrue($ret); 

    }

    function test_getInstanceHandles() {
        $CM = new LogManager();
        $CM->RegisterAllPlugins();

        $Obj1 = $CM->getInstanceHandle("Mutil", array(dirname(__FILE__)."/data/AAAA.log"));
        $Obj2 = $CM->getInstanceHandle("Mutil", array(dirname(__FILE__)."/data/AAAA.log"));

        $this->assertEquals($Obj2, $Obj1);
        $this->assertEquals(count($CM->getInstanceHandles()), 1);
        $ret = $CM->getInstanceHandles();
        var_dump($ret );
        $this->assertEquals(gettype($ret['AAAA.log']), 'object');
    }

    function test_getInstanceHandle1() {
        $CM = new LogManager();
        $CM->RegisterAllPlugins();
        
        LogManager::RegisterAllPlugins();

        LogManager::RegisterPlugin('BBBLog');

        $this->assertTrue(in_array('BBBLog',LogManager::getPluginMode()));
        $Obj = $CM->getInstanceHandle("BBBLog");
        
        $Obj1 = $CM->getInstanceHandle("BBBLog");

        $this->assertEquals($Obj, $Obj1);
        $this->assertEquals(count($CM->getInstanceHandles()), 1);

        $ret = $CM->getInstanceHandles();
        $this->assertEquals(gettype($ret['aaa.log']), 'object');        

        $Obj2 = $CM->getInstanceHandle("dddd");

        $this->assertEquals($Obj2, null);
    }

    function test_check() {
        $this->assertTrue(LogManager::check("BBBLog"));
        $this->assertFalse(LogManager::check("AAALog"));
    }

}

?>
