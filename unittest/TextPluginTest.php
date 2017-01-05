<?php
require_once(dirname(__FILE__)."/../src/confmanager.php");
class TestTextPlugin extends PHPUnit_Framework_TestCase {
    function setup() {
        ConfigManager::RegisterAllPlugins();
        $CM = new ConfigManager();
        $CM->RegisterAllPlugins();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/Text.conf"));
        var_dump($Obj);
    }

    function test_items() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/Text.conf"));
        $ret = $Obj->items($section='aaa', $sep=':');
        var_dump($ret);
        var_dump(array_keys($ret));
        $this->assertTrue(in_array('A', array_keys($ret)));
        $this->assertTrue(in_array('b', array_values($ret)));
        $this->assertEquals(count($ret), 3);

        $ret = $Obj->items($section='aaa', $sep='=');
        $this->assertEquals(count($ret), 2);
        var_dump($ret);
        $this->assertTrue(in_array('B', array_keys($ret)));
        $this->assertTrue(in_array('c', array_values($ret)));

        $ret = $Obj->items($section='ccc', $sep='=');
        $this->assertEquals(count($ret), 0);

        $ret = $Obj->items($section='ccc', $sep=':');
        $this->assertEquals(count($ret), 2);
        var_dump($ret);
        $this->assertTrue(in_array('A', array_keys($ret)));
        $this->assertTrue(in_array('B', array_keys($ret)));
        $this->assertTrue(in_array('e', array_values($ret)));
        $this->assertTrue(in_array('d', array_values($ret)));

        $ret = $Obj->items($section='', $sep=':');
        var_dump($ret);
        $this->assertEquals(count($ret), 1);
        $this->assertTrue(in_array('G', array_keys($ret)));
        $this->assertTrue(in_array('aaa', array_values($ret)));
        
    }

    function test_items1() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/TextNoSection.conf"));
        
        $ret = $Obj->items($section='aaa', $sep=':');
        $this->assertEquals(count($ret), 0);

        $ret = $Obj->items($section='', $sep=':');
        var_dump($ret);
        $this->assertTrue(in_array('A', array_keys($ret)));
        $this->assertTrue(in_array('b', array_values($ret)));
        $this->assertEquals(count($ret), 3);

        $ret = $Obj->items($section='', $sep='=');
        var_dump($ret);
        $this->assertTrue(in_array('A', array_keys($ret)));
        $this->assertTrue(in_array('e', array_values($ret)));
        $this->assertEquals(count($ret), 3);
    }

    function test_keys() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/Text.conf"));
        $ret = $Obj->keys($section='aaa', $sep=':');
        var_dump($ret);
        $this->assertEquals(count($ret), 3);
        $this->assertTrue(in_array('A', array_values($ret)));
        
        $ret = $Obj->keys($section='', $sep=':');
        var_dump($ret);
        $this->assertEquals(count($ret), 1);
        $this->assertTrue(in_array('G', array_values($ret)));
    }

    function test_keys1() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/TextNoSection.conf"));
        $ret = $Obj->keys($section='aaa', $sep=':');
        var_dump($ret);
        $this->assertEquals(count($ret), 0);
    
        $ret = $Obj->keys($section='', $sep='=');
        var_dump($ret);
        $this->assertTrue(in_array('A', array_values($ret)));
        $this->assertEquals(count($ret), 3);
        
    }

    function test_values() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/Text.conf"));
        $ret = $Obj->values($section='aaa', $sep=':');
        var_dump($ret);
        $this->assertEquals(count($ret), 3);
        $this->assertTrue(in_array('b', array_values($ret)));
        
        $ret = $Obj->values($section='', $sep=':');
        var_dump($ret);
        $this->assertEquals(count($ret), 1);
        $this->assertTrue(in_array('aaa', array_values($ret)));
    }

    function test_values1() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/TextNoSection.conf"));
        $ret = $Obj->values($section='aaaa', $sep=':');
        var_dump($ret);
        $this->assertEquals(count($ret), 0);
    
        $ret = $Obj->values($section='', $sep='=');
        var_dump($ret);
        $this->assertTrue(in_array('e', array_values($ret)));
        $this->assertEquals(count($ret), 3);
    }

    function test_setAttr() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/TextNoSection.conf"));
        $Obj->setAttr();
        var_dump($Obj);
        var_dump($Obj->_prefix);
        $this->assertEquals($Obj->_prefix, "\t");
        $this->assertEquals($Obj->_sep, ':');
        $this->assertEquals($Obj->_blank, True);

        $Obj->setAttr('=', False, $prefix="\r");
        $this->assertEquals($Obj->_prefix, "\r");
        $this->assertEquals($Obj->_sep, '=');
        $this->assertEquals($Obj->_blank, False);
        
        $Obj->setAttr( $prefix="\r", $sep='=', $blank=False);
        $this->assertEquals($Obj->_prefix, False);
        $this->assertEquals($Obj->_sep, "\r");
        $this->assertEquals($Obj->_blank, '=');
    }

    function test_get() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/TextNoSection.conf"));
        try {
            $Obj->get();
            $this->assertTrue(False);
        } catch(AbstractInterface $e) {
            $e->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
        
        var_dump(get_class_methods($Obj));
        try {
            $ret = $Obj->aaaaaa();
        } catch (CallFunctionFail $e) {
            $e->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }

        $ret = $Obj->get($key="A", $sep='=', $section='');
        $this->assertEquals('e', $ret);

        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/Text.conf"));
        $ret = $Obj->get($key="A", $sep=':', $section='aaa');
        var_dump($ret);
        $this->assertEquals('b', $ret);
        try {
            $ret = $Obj->get($key="A", $sep='=', $section='aaa');
            var_dump($ret);
            $this->assertTrue(false);
        } catch (KeyNotFound $e) {
            $e->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
        $ret = $Obj->get($key="A", $sep=':', $section='aaa.@aa');
        var_dump($ret);
        $this->assertEquals('bba', $ret);

        $ret = $Obj->get($key="C[1]", $sep=':', $section='aaa');
        $this->assertEquals('bb', $ret);
        $ret = $Obj->get($key="C[0]", $sep=':', $section='aaa');
        $this->assertEquals('aa', $ret);
        
        $ret = $Obj->get($key="D", $sep=':', $section='aaa');
        $this->assertEquals('bb', $ret);
        
        $ret = $Obj->get($key="D", $sep='=', $section='aaa');
        $this->assertEquals('dd', $ret);
    }
    
    function test__Get() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/TextNoSection.conf"));
        $this->assertEquals(null, $Obj->aaaa);
    }

    function test_getSectionSize() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/TextNoSection.conf"));
        $ret = $Obj->getSectionSize("","");
        var_dump($ret);
        $this->assertEquals(0, $ret);
        $ret = $Obj->getSectionSize("aaa","");
        var_dump($ret);
        $this->assertEquals(0, $ret);

        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/Text.conf"));
        $ret = $Obj->getSectionSize("","");
        var_dump($ret);
        $this->assertEquals(0, $ret);
        $ret = $Obj->getSectionSize("aaa","");
        var_dump($ret);
        $this->assertEquals(0, $ret);
        $ret = $Obj->getSectionSize("aa","aaa");
        var_dump($ret);
        $this->assertEquals(1, $ret);
        $ret = $Obj->getSectionSize("bb","aaa");
        var_dump($ret);
        $this->assertEquals(2, $ret);
        $ret = $Obj->getSectionSize("","aaa");
        var_dump($ret);
        $this->assertEquals(0, $ret);
    }

    function test_hasKey() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/TextNoSection.conf"));
        $ret = $Obj->has_key('A');
        $this->assertTrue($ret);

        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/Text.conf"));
        $ret = $Obj->has_key('A');
        $this->assertFalse($ret);
        
        $ret = $Obj->has_key('G');
        $this->assertTrue($ret);
        
        $ret = $Obj->has_key('A', ":", "aaa");
        $this->assertTrue($ret);
        
        $ret = $Obj->has_key('A', "=", "aaa");
        $this->assertFalse($ret);
    
        try {
            $ret = $Obj->has_key();
            $this->assertTrue(False);
        } catch (AbstractInterface $e) {
            $this->assertTrue(True);
        }
    }

    function test_set() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/tmpNoSection.conf"));
        $ret = $Obj->set('G', 'a', 'sep', '=', 'section', 'abc', 'blank', True);
        $this->assertTrue($ret);
        $ret = $Obj->has_key('G', '=', "abc");
        $this->assertTrue($ret);

        $Obj->execute("rm -rf ".dirname(__FILE__)."/conf/tmpNoSection.conf");
    }
    
    function test_set1() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/tmpNo1Section.conf"));
        $ret = $Obj->set('A', 'a', 'B', 'b', 'C', 'c');
        $this->assertTrue($ret);
        $ret = $Obj->has_key('A');
        $this->assertTrue($ret);
        $ret = $Obj->has_key('C');
        $this->assertTrue($ret);
        $ret = $Obj->has_key('B');
        $this->assertTrue($ret);
        $ret = $Obj->set('A', 'b');
        $this->assertTrue($ret);

        $ret = $Obj->get('A');
        $this->assertEquals('b', $ret);

        try {
            $ret = $Obj->set();
            $this->assertTrue(False);
        } catch (AbstractInterface $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
        $Obj->execute("rm -rf ".dirname(__FILE__)."/conf/tmpNo1Section.conf");
    }

    function test_set2() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/tmpNo2Section.conf", "127.0.0.1", "", True, True));

        $ret = $Obj->set('A', 'a', 'B', array(1,2), 'sep', '=', 'section', 'abc', 'blank', false);
        $this->assertTrue($ret);

        $ret = $Obj->get('A', '=', 'abc');
        $this->assertEquals('a', $ret);

        $ret = $Obj->get('B[0]', '=', 'abc');
        $this->assertEquals('1', $ret);
                
        $ret = $Obj->get('B[1]', '=', 'abc');
        $this->assertEquals('2', $ret);

        $Obj->execute("rm -rf ".dirname(__FILE__)."/conf/tmpNo2Section.conf");
    }

    function test_delete() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/tmpNo3Section.conf", "127.0.0.1", "", True, True));
        $ret = $Obj->set('A', 'a', 'B', array(1,2), 'sep', '=', 'section', 'abc', 'blank', false);
        $this->assertTrue($ret);
        
        $ret = $Obj->get('A', '=', 'abc');
        $this->assertEquals('a', $ret);

        $ret = $Obj->delete('A');
        $this->assertEquals(null, $ret);
        
        $ret = $Obj->delete('A', 'abc', "=");
        $this->assertEquals(true, $ret);

        try {
            $ret = $Obj->get('A', '=', 'abc');
            $this->assertTrue(False);
        } catch (KeyNotFound $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }

        try {
            $ret = $Obj->delete('@B', '');
            $this->assertTrue(False);
        } catch (AbstractInterface $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }

        $Obj->execute("rm -rf ".dirname(__FILE__)."/conf/tmpNo3Section.conf");
    }

    function test_add() {
        $CM = new ConfigManager();
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/tmpNo4Section.conf", "127.0.0.1", "", False, True));
        $ret = $Obj->set('A', 'a', 'B', array(1,2), 'sep', '=', 'section', 'abc', 'blank', false);
        $this->assertTrue($ret);

        $ret = $Obj->add('A', 'b', 'abd', ':');
        $this->assertTrue($ret);

        $ret = $Obj->get('A', ':', 'abd');
        $this->assertEquals('b', $ret);
        
        $ret = $Obj->add('A', 'b', 'abc', '=');
        $this->assertTrue($ret);
        
        $ret = $Obj->get('A', '=', 'abc');
        $this->assertEquals('b', $ret);

        $ret = $Obj->add('A', 'b');
        $this->assertTrue($ret);

        try {
            $Obj->add("a");
            $this->assertTrue(False);
        } catch (AbstractInterface $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }

        $Obj->execute("rm -rf ".dirname(__FILE__)."/conf/tmpNo4Section.conf");
    }

    function test_iset() {
        $CM = new ConfigManager();
        exec("rm -rf ".dirname(__FILE__)."/conf/tmpNo5Section.conf");
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/tmpNo5Section.conf", "127.0.0.1", "", False, True));
        $ret = $Obj->set('A', 'a', 'B', array(1,2), "C", 1, 'sep', '=', 'section', 'abc', 'blank', false);
        $this->assertTrue($ret);
        
        $ret = $Obj->iset('A', 'b', '=', 'abc');
        $this->assertTrue($ret);

        $ret = $Obj->get('A', '=', 'abc');
        $this->assertEquals('ab', $ret);
        
        $ret = $Obj->iset('C', 1, '=', 'abc');
        $this->assertTrue($ret);

        $ret = $Obj->get('C', '=', 'abc');
        $this->assertEquals(2, $ret);
        
        $ret = $Obj->iset('D', 1, '=', 'abc');
        $this->assertTrue($ret);

        $ret = $Obj->get('D', '=', 'abc');
        $this->assertEquals(1, $ret);

        $ret = $Obj->iset('D', "aa");
        $this->assertTrue($ret);

        try {
            $Obj->iset('D');
            $this->assertTrue(false);
        } catch (AbstractInterface $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }       

        $Obj->execute("rm -rf ".dirname(__FILE__)."/conf/tmpNo5Section.conf");
    }

    function test_KeyIndexOutOfSize() {
        try {
            throw new KeyIndexOutOfSize('test', RETTYPE::ERR);
        } catch (KeyIndexOutOfSize $e) {
            $e->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
    }
    
   function test_SectionIndexOutOfSize() {
        try {
            throw new SectionIndexOutOfSize('test', RETTYPE::ERR);
        } catch (SectionIndexOutOfSize $e) {
            $e->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
    }

    function test_isChange() {
        $CM = new ConfigManager();
        exec("rm -rf ".dirname(__FILE__)."/conf/tmpNo6Section.conf");
        $Obj = $CM->getInstanceHandle("text", array(dirname(__FILE__)."/conf/tmpNo5Section.conf", "127.0.0.1", "", True, True));
        var_dump($Obj->md5sum());

        $ret = $Obj->set('A', 'a', 'B', array(1,2), "C", 1, 'sep', '=', 'section', 'abc', 'blank', false);
        $this->assertTrue($ret);

        var_dump($Obj->md5sum());
        $this->assertTrue($Obj->isChange());

        $ret = $Obj->get('A', '=', 'abc');
        $this->assertEquals('a', $ret);
        
        $this->assertFalse($Obj->isChange());
        $Obj->execute("rm -rf ".dirname(__FILE__)."/conf/tmpNo6Section.conf");
    }
}
?>
