<?php
require_once(dirname(__FILE__)."/../src/dictmanager.php");

class AAADict {
    const MODE = "aaaa";
}

class BBBDict {
    const MODE = "BBBDict";
    Const CLASSNAME = __CLASS__;
    function basename() {
        return "aaa.dict";
    }
}

class TestDictmanager extends PHPUnit_Framework_TestCase {
    function test_registerPlugin () {
        DictManager::RegisterAllPlugins();
        $CM = new DictManager();
        $CM->RegisterAllPlugins();
        $this->assertTrue(in_array('tdbm',DictManager::getPluginMode()));
        $this->assertTrue(in_array('tdbm',DictManager::getPluginMode()));
        try {
            $CM->RegisterPlugin('Exception');
            $this->assertTrue(False);
        } catch (RegisterFail $e) {
            $e->__toString();
            $this->assertEquals($e->getcode(), -1);
        }
        $this->assertTrue(!in_array('Exception', $CM->getPluginMode()));
        unset($CM);
        var_dump(DictManager::getPluginMode());
        $this->assertTrue(in_array('tdbm',DictManager::getPluginMode()));
    }


    function test_registerPlugin2 () {
        var_dump(DictManager::getPluginMode());
        $CM = new DictManager();
        try {
            DictManager::RegisterPlugin('AAADict');
            $this->assertTrue(False);
        } catch (RegisterFail $e) {
            $e->__toString();       
            $this->assertEquals($e->getcode(), -1);
        }
        
        try {
            DictManager::RegisterPlugin('aaaaaaaaaaaaaaaaaaaa');
            $this->assertTrue(False);
        } catch (RegisterFail $e) {
            $e->__toString();       
            $this->assertEquals($e->getcode(), -1);
        }
    }


    function test_initialize() {
        $this->assertTrue(DictManager::initialize('aa'));
    }

    function test_getInstanceHandle() {
        $CM = new DictManager();
        $CM->RegisterAllPlugins();

        $Obj = $CM->getInstanceHandle("tdbm", array(dirname(__FILE__)."/data/test.dict"));
        $this->assertEquals(gettype($Obj), "object");
        $this->assertEquals($Obj->basename(), "test.dict"); 

        $this->assertEquals(count($CM->getConfigInstance()), 1);

        $Obj1 = $CM->getInstanceHandle("tdbm", array(dirname(__FILE__)."/data/AAAA.dict"));

        $this->assertEquals(gettype($Obj1), "object");
        $this->assertEquals($Obj1->basename(), "AAAA.dict");
        $this->assertEquals(count($CM->getConfigInstance()), 2);
        $this->assertEquals(gettype($CM->getInstanceHandle("tdbm", array(dirname(__FILE__)."/data/test.dict"))), 'object');

        $this->assertEquals(count($CM->getConfigInstance()), 2);

        $CM->unRegisterAllInstance();
        $this->assertEquals(count($CM->getConfigInstance()), 0);
        
        $CM->unRegisterAllInstance();
        $this->assertEquals(count($CM->getConfigInstance()), 0);
    }

    function test_unRegisterInstance() {
        $CM = new DictManager();
        $CM->RegisterAllPlugins();

        $Obj1 = $CM->getInstanceHandle("tdbm", array(dirname(__FILE__)."/data/AAAA.dict"));        

        $this->assertTrue(in_array('AAAA.dict', $CM->getConfigInstance())); 

        $ret = $CM->unRegisterInstance('AAAA.dict');
        $this->assertTrue(!in_array('',$CM->getConfigInstance()));     
        $this->assertTrue($ret);

        $ret = $CM->unRegisterInstance('aaaaa'); 
        $this->assertTrue(!in_array('tdbm',$CM->getConfigInstance()));
        $this->assertTrue($ret); 

    }

    function test_getInstanceHandles() {
        $CM = new DictManager();
        $CM->RegisterAllPlugins();

        $Obj1 = $CM->getInstanceHandle("tdbm", array(dirname(__FILE__)."/data/AAAA.dict"));
        $Obj2 = $CM->getInstanceHandle("tdbm", array(dirname(__FILE__)."/data/AAAA.dict"));

        $this->assertEquals($Obj2, $Obj1);
        $this->assertEquals(count($CM->getInstanceHandles()), 1);
        $ret = $CM->getInstanceHandles();
        var_dump($ret );
        $this->assertEquals(gettype($ret['AAAA.dict']), 'object');
    }

    function test_getInstanceHandle1() {
        $CM = new DictManager();
        $CM->RegisterAllPlugins();
        
        DictManager::RegisterAllPlugins();

        DictManager::RegisterPlugin('BBBDict');

        $this->assertTrue(in_array('BBBDict',DictManager::getPluginMode()));
        $Obj = $CM->getInstanceHandle("BBBDict");
        
        $Obj1 = $CM->getInstanceHandle("BBBDict");

        $this->assertEquals($Obj, $Obj1);
        $this->assertEquals(count($CM->getInstanceHandles()), 1);

        $ret = $CM->getInstanceHandles();
        $this->assertEquals(gettype($ret['aaa.dict']), 'object');        

        $Obj2 = $CM->getInstanceHandle("dddd");

        $this->assertEquals($Obj2, null);
    }

    function test_check() {
        $this->assertTrue(DictManager::check("BBBDict"));
        $this->assertFalse(DictManager::check("AAADict"));
    }

}

?>
