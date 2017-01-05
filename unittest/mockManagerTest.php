<?php
require_once(dirname(__FILE__)."/../src/mockmanager.php");

class AAAMock {
    const MODE = "aaaa";
}

class BBBMock {
    const MODE = "BBBMock";
    Const CLASSNAME = __CLASS__;
    public $_mockname = "B1";
}

class TestMockmanager extends PHPUnit_Framework_TestCase {
    function test_registerPlugin () {
        MockManager::RegisterAllPlugins();
        $CM = new MockManager();
        $CM->RegisterAllPlugins();
        $this->assertTrue(in_array('HttpMockServer',MockManager::getPluginMode()));
        $this->assertTrue(in_array('KfcMockServer',MockManager::getPluginMode()));
        try {
            $CM->RegisterPlugin('Exception');
            $this->assertTrue(False);
        } catch (RegisterFail $e) {
            $e->__toString();
            $this->assertEquals($e->getcode(), -1);
        }
        $this->assertTrue(!in_array('Exception', $CM->getPluginMode()));
        unset($CM);
        var_dump(MockManager::getPluginMode());
        $this->assertTrue(in_array('HttpMockServer',MockManager::getPluginMode()));
        $this->assertTrue(in_array('KfcMockServer',MockManager::getPluginMode()));
    }


    function test_registerPlugin2 () {
        var_dump(MockManager::getPluginMode());
        $CM = new MockManager();
        try {
            MockManager::RegisterPlugin('AAAMock');
            $this->assertTrue(False);
        } catch (RegisterFail $e) {
            $e->__toString();       
            $this->assertEquals($e->getcode(), -1);
        }
        
        try {
            MockManager::RegisterPlugin('aaaaaaaaaaaaaaaaaaaa');
            $this->assertTrue(False);
        } catch (RegisterFail $e) {
            $e->__toString();       
            $this->assertEquals($e->getcode(), -1);
        }
    }


    function test_initialize() {
        $this->assertTrue(MockManager::initialize('aa'));
    }

    function test_getInstanceHandle() {
        $CM = new MockManager();
        $CM->RegisterAllPlugins();

        $Obj = $CM->getInstanceHandle("HttpMockServer", array("test.conf"));
        $this->assertEquals(gettype($Obj), "object");
        $this->assertEquals($Obj->_mockname, "test.conf"); 

        $this->assertEquals(count($CM->getConfigInstance()), 1);

        $Obj1 = $CM->getInstanceHandle("HttpMockServer", array("AAAA.conf"));

        $this->assertEquals(gettype($Obj1), "object");
        $this->assertEquals($Obj1->_mockname, "AAAA.conf");
        $this->assertEquals(count($CM->getConfigInstance()), 2);
        $this->assertEquals(gettype($CM->getInstanceHandle("HttpMockServer", array("test.conf"))), 'object');

        $this->assertEquals(count($CM->getConfigInstance()), 2);

        $CM->unRegisterAllInstance();
        $this->assertEquals(count($CM->getConfigInstance()), 0);
        
        $CM->unRegisterAllInstance();
        $this->assertEquals(count($CM->getConfigInstance()), 0);
    }

    function test_unRegisterInstance() {
        $CM = new MockManager();
        $CM->RegisterAllPlugins();

        $Obj1 = $CM->getInstanceHandle("HttpMockServer", array("AAAA.conf"));        

        $this->assertTrue(in_array('AAAA.conf',$CM->getConfigInstance())); 

        $ret = $CM->unRegisterInstance('AAAA.conf');
        $this->assertTrue(!in_array('',$CM->getConfigInstance()));     
        $this->assertTrue($ret);

        $ret = $CM->unRegisterInstance('aaaaa'); 
        $this->assertTrue(!in_array('KfcMockServer',$CM->getConfigInstance()));
        $this->assertTrue($ret); 

    }

    function test_getInstanceHandles() {
        $CM = new MockManager();
        $CM->RegisterAllPlugins();

        $Obj1 = $CM->getInstanceHandle("HttpMockServer", array("AAAA.conf"));
        $Obj2 = $CM->getInstanceHandle("HttpMockServer", array("AAAA.conf"));

        $this->assertEquals($Obj2, $Obj1);
        $this->assertEquals(count($CM->getInstanceHandles()), 1);
        $ret = $CM->getInstanceHandles();
        var_dump($ret );
        $this->assertEquals(gettype($ret['AAAA.conf']), 'object');
    }

    function test_getInstanceHandle1() {
        $CM = new MockManager();
        $CM->RegisterAllPlugins();
        
        MockManager::RegisterAllPlugins();

        MockManager::RegisterPlugin('BBBMock');

        $this->assertTrue(in_array('BBBMock',MockManager::getPluginMode()));
        $Obj = $CM->getInstanceHandle("BBBMock");
        
        $Obj1 = $CM->getInstanceHandle("BBBMock");

        $this->assertEquals($Obj, $Obj1);
        $this->assertEquals(count($CM->getInstanceHandles()), 1);

        $ret = $CM->getInstanceHandles();
        $this->assertEquals(gettype($ret['B1']), 'object');        

        $Obj2 = $CM->getInstanceHandle("dddd");

        $this->assertEquals($Obj2, null);
    }

    function test_check() {
        $this->assertTrue(MockManager::check("BBBMock"));
        $this->assertFalse(MockManager::check("AAAMock"));
    }

}

?>
