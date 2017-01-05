<?php
require_once(dirname(__FILE__)."/../src/confmanager.php");
class TestYamlPluginSuite  extends PHPUnit_Framework_TestCase {
    function test_getKeys() {
        logging::info("start function:", __FUNCTION__);
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle('yaml', array(dirname(__FILE__)."/conf/mergerFrame.yaml"));
        $ret = $Obj->getKeys($Obj->_Yaml);
        print_r($ret);
        $this->assertGreaterThan(0, count($ret));
        $ret = $Obj->getValues('mainsection.log4cpp_conf', $Obj->_Yaml);
        print_r($ret."\n");
        $this->assertEquals("/home/a/search/conf/log4cpp-merger.conf", $ret['mainsection.log4cpp_conf']);
        $ret = $Obj->getValues('log4cpp_conf', $Obj->_Yaml);
        $this->assertEquals("/home/a/search/conf/log4cpp-merger.conf", $ret['mainsection.log4cpp_conf']);

        try {
            $ret = $Obj->getValues('og4cpp_conf', $Obj->_Yaml);
        } catch (KeyNotFound $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
    }

    function test_get() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle('yaml', array(dirname(__FILE__)."/conf/mergerFrame.yaml"));
        
        $ret = $Obj->get('mainsection.log4cpp_conf');
        $this->assertEquals("/home/a/search/conf/log4cpp-merger.conf", $ret['mainsection.log4cpp_conf']);

        $ret = $Obj->get('log4cpp_conf');
        $this->assertEquals("/home/a/search/conf/log4cpp-merger.conf", $ret['mainsection.log4cpp_conf']);
        
        $ret = $Obj->get('sequence');
        $this->assertEquals("array", gettype($ret));
        
        $ret = $Obj->get('sequence');
        //var_dump($ret);
        $this->assertTrue(in_array("QueryParserHandler", $ret));

        try {
            $ret = $Obj->get();
            $this->assertTrue(false);
        } catch (AbstractInterface $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
    }
    
    function test_set() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle('yaml', array(dirname(__FILE__)."/conf/mergerFrame.yaml"));
        $ret = $Obj->set('mainsection.log4cpp_conf', 'aaa');
        $this->assertTrue($ret);
        $ret = $Obj->get('log4cpp_conf');
        $this->assertEquals("aaa", $ret['mainsection.log4cpp_conf']);
        
        $ret = $Obj->set('log4cpp_category', 'aaa');        
        $this->assertTrue($ret);
        $ret = $Obj->get('log4cpp_category');
        $this->assertEquals("aaa", $ret['log4cpp_category']);

        $ret = $Obj->get('mainsection.log4cpp_category');
        $this->assertEquals("merger", $ret['mainsection.log4cpp_category']);

        $ret = $Obj->set("aaa", 'aaa', 'bbb', 'bbb');
        $this->assertTrue($ret);
        $ret = $Obj->get('aaa');
        $this->assertEquals("aaa", $ret['aaa']);

        $ret = $Obj->get('bbb');
        $this->assertEquals("bbb", $ret['bbb']);

        try {
            $Obj->set();
            $this->assertTrue(false);
        } catch (AbstractInterface $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
    }

    function test__get() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle('yaml', array(dirname(__FILE__)."/conf/mergerFrame.yaml"));

        $this->assertEquals(null, $Obj->aaa);
    }

    function test__call() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle('yaml', array(dirname(__FILE__)."/conf/mergerFrame.yaml"));
        try {
            $Obj->aaa();
            $this->assertTrue(false);
        } catch (CallFunctionFail $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
    }
    function test_delete() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle('yaml', array(dirname(__FILE__)."/conf/mergerFrame.yaml"));

        $ret = $Obj->delete('mainsection');
        $this->assertTrue($ret);

        try {
            $ret = $Obj->get('mainsection');
            $this->assertTrue(false); # bugs
        } catch (KeyNotFound $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }

        $ret = $Obj->delete('mainsection.log4cpp_conf');
        try {
            $ret = $Obj->get('mainsection.log4cpp_conf');
            $this->assertTrue(false);
        } catch (KeyNotFound $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
        

        try {
            $Obj->delete();
            $this->assertTrue(false);
        } catch(AbstractInterface $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
    
        $ret = $Obj->delete('aaaaaaaa');
        $this->assertTrue($ret);
        
        try {
            $ret = $Obj->get('aaaaaaaa');
            $this->assertTrue(false);
        } catch (KeyNotFound $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
    }

    function test_has_key() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle('yaml', array(dirname(__FILE__)."/conf/mergerFrame.yaml"));

        try {
            $Obj->has_key();
            $this->assertTrue(false);
        } catch(AbstractInterface $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
    
    }

    function test_add() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle('yaml', array(dirname(__FILE__)."/conf/mergerFrame.yaml"));

        try {
            $Obj->add();
            $this->assertTrue(false);
        } catch(AbstractInterface $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
        
        $ret = $Obj->add('aaa', 'aaa');
        $this->assertTrue($ret);
        $ret = $Obj->get('aaa');
        $this->assertEquals('aaa', $ret['aaa']);

        $ret = $Obj->add('mainsection.log4cpp_conf', 'aaa');
        $this->assertTrue($ret);
        $ret = $Obj->get('mainsection.log4cpp_conf');
        $this->assertEquals('aaa', $ret['mainsection.log4cpp_conf']);

        $ret = $Obj->add('A.B.C', 'aaa');
        $this->assertTrue($ret);
        $ret = $Obj->get('A.B.C');
        $this->assertEquals('aaa', $ret['A.B.C']);

        $ret = $Obj->get('A.B');
        $this->assertEquals(array('C'=>"aaa"), $ret['A.B']);
    }

    function test_iset() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle('yaml', array(dirname(__FILE__)."/conf/mergerFrame.yaml"));

        try {
            $Obj->iset();
            $this->assertTrue(false);
        } catch(AbstractInterface $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
         
        $ret = $Obj->set('aaa', 'aaa');
        $this->assertTrue($ret);
        $ret = $Obj->iset('aaa', 'bbb');
        $ret = $Obj->get('aaa');
        $this->assertEquals('aaabbb', $ret['aaa']);

        $ret = $Obj->set('bbb', 1);
        $this->assertTrue($ret);
        $ret = $Obj->iset('bbb', 1);
        $ret = $Obj->get('bbb');
        $this->assertEquals(2, $ret['bbb']);

        $ret = $Obj->iset('ccc', 1);
        $ret = $Obj->get('ccc');
        $this->assertEquals(1, $ret['ccc']);

        $ret = $Obj->iset('sequence', 1);
        $ret = $Obj->get('sequence');
        $this->assertEquals("1", $ret['sequence']);
        
        
    }

    function test_isChange() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle('yaml', array(dirname(__FILE__)."/conf/mergerFrame.yaml"));
        
        $ret = $Obj->set('A', 'a', 'B', array(1,2));
        $this->assertTrue($ret);

        var_dump($Obj->md5sum());
        $this->assertTrue($Obj->isChange());

        $ret = $Obj->get('A');
        $this->assertEquals('a', $ret['A']);
    
        $this->assertFalse($Obj->isChange());
    }  
}
?>

