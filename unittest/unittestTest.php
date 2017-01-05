<?php
require_once(dirname(__FILE__)."/../src/asserts.php");
class TestAsserts extends PHPUnit_Framework_TestCase {
    public function setup() {
        asserts::setMode(asserts::ThrowExcept);
    }

    function test_assertEqual() {
        $Un = new asserts();
        $ret = $Un->assertEqual('aaa', "aaa", "not Equal Test");
        $this->assertEquals(true, $ret);

        try {
            $ret = $Un->assertEqual('aaa', "aa", "not Equal Test");
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertEqual('aaa', "aa", "not Equal Test");
        $this->assertFalse($ret); 
        asserts::setMode(asserts::ThrowExcept);

        try {
            $ret = $Un->assertEqual(array(1), array(2));
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertEqual("", "1");
            $this->assertTrue(False);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertEqual("", Null);
            $this->assertTrue(False);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertEqual(0, 'a');
            $this->assertTrue(False);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        $ret = $Un->assertEqual(0, '0');
        $this->assertTrue(True);
        $ret = $Un->assertEqual("", "");
        $this->assertTrue(True);
	}

    function test_assertNotEqual() {
        $Un = new asserts();
        try {
            $ret = $Un->assertNotEqual(Null, Null);
            $this->assertTrue(False);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        try {
            $ret = $Un->assertNotEqual(Array(), Array(), 'Test Err');
            $this->assertTrue(False);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertNotEqual(Array(), Array(), 'Test Err');
        $this->assertFalse($ret); 
        asserts::setMode(asserts::ThrowExcept);

        $ret = $Un->assertNotEqual(Null, "");
        $this->assertTrue(True);
        $ret = $Un->assertNotEqual('1', 1);
        $this->assertTrue(True);
    }



    function test_assertGreater() {
        $Un = new asserts();
        $ret = $Un->assertGreater("100", "101", "greater Test");
        $this->assertEquals(true, $ret);

        try {
            $ret = $Un->assertGreater("1", "1");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertGreater("101", "100", "Test Error");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertGreater("qa", "ew");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertGreater("", "");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertGreater("123asd", "");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertGreater("123asd", "");
        $this->assertFalse($ret); 
        asserts::setMode(asserts::ThrowExcept);  
    }

    function test_assertGreaterEqual() {
        $Un = new asserts();
        $ret = $Un->assertGreaterEqual("123asd", Array(123));
        $this->assertEquals(true, $ret);

        $ret = $Un->assertGreaterEqual('1','1');
        $this->assertEquals(true, $ret);
        $ret = $Un->assertGreaterEqual('1','100');
        $this->assertEquals(true, $ret);
        $ret = $Un->assertGreaterEqual('',"");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertGreaterEqual(null, null);
        $this->assertEquals(true, $ret);
        try {
            $ret = $Un->assertGreaterEqual("123asd", "");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
       
        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertGreaterEqual("123asd", "");
        $this->assertFalse($ret); 
        asserts::setMode(asserts::ThrowExcept);  
        
        try {
            $ret = $Un->assertGreaterEqual(Array(1), Array(0), "test Err");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
    }

    function test_assertLess() {
        $Un = new asserts();
        $ret = $Un->assertLess("101", "100", "less Test");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertLess("bb", "aa", "less Test");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertLess(Array(1), Array(), "less Test");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertLess(Array(), "", "less Test");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertLess("", -1, "less Test");
        $this->assertEquals(true, $ret);

        try {
            $ret = $Un->assertLess("1", "1", "Test Error");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertLess("100", "1002");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertLess("aa", "bb");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertLess("", "");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertLess("", "");
        $this->assertFalse($ret); 
        asserts::setMode(asserts::ThrowExcept);  

        try {
            $ret = $Un->assertLess("123asd", "223asd");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

    }

    function test_assertLessEqual() {
        $Un = new asserts();
        $ret = $Un->assertLessEqual("101", "100", "less Test");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertLessEqual("100", "100", "less Test");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertLessEqual("aa", "aa", "less Test");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertLessEqual("bb", "aa", "less Test");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertLessEqual(Array(1), Array(), "less Test");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertLessEqual(Array(), "", "less Test");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertLessEqual("", -1, "less Test");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertLessEqual("", 0, "less Test");
        $this->assertEquals(true, $ret);

        try {
            $ret = $Un->assertLessEqual("1", "2", "Test Error");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        
        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertLessEqual("1", "2", "Test Error");
        $this->assertFalse($ret); 
        asserts::setMode(asserts::ThrowExcept);  
    }

    function test_assertInArray() {
        $Un = new asserts();
        $ret = $Un->assertInArray("101", array("100","101","101a","103"), "InArray Test");
        $this->assertEquals(true, $ret);

        $ret = $Un->assertInArray("101", array("10"=>"100","101a"=>"101","12"=>"102"), "InArray Test");
        $this->assertEquals(true, $ret);

        try {
            $ret = $Un->assertInArray("a",array("100","101","103"), 'test Err');
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertInArray("100", "1002");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        
        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertInArray("a",array("100","101","103"), 'test Err');
        $this->assertFalse($ret); 
        asserts::setMode(asserts::ThrowExcept);  

        try {
            $ret = $Un->assertInArray("aaa", array("aaa"=>"100","101a"=>"101"));
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertInArray("", "");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertInArray("123asd", "");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
    }

    function test_assertKeyInArray() {
        $Un = new asserts();
        $ret = $Un->assertKeyInArray("101", array("101"=>"100","101a"=>"101a","12"=>"102"), "KeyInArray Test");
        $this->assertEquals(true, $ret);

        try {
            $ret = $Un->assertKeyInArray("a",array("100","101","103"), "test");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertKeyInArray("100", "1002");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertKeyInArray("aaa", array("bbb"=>"100","101a"=>"101"));
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        asserts::setMode(10);
        try {
            $ret = $Un->assertKeyInArray("aaa", array("bbb"=>"100","101a"=>"101"));
        } catch (Exception $e) {
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        asserts::setMode(1);
        $ret = $Un->assertKeyInArray("aaa", array("bbb"=>"100","101a"=>"101"));
        $this->assertFalse($ret); 
        asserts::setMode(asserts::ThrowExcept);  

        try {
            $ret = $Un->assertKeyInArray("", "", "Test");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertKeyInArray("123asd", "");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
    }

    function test_assertNotInArray() {
        $Un = new asserts();
        $ret = $Un->assertNotInArray("101111", array("100","101","101a","103"), "InArray Test");
        $this->assertEquals(true, $ret);

        $ret = $Un->assertNotInArray("101a", array("10"=>"100","101a"=>"101","12"=>"102"), "InArray Test");
        $this->assertEquals(true, $ret);

        try {
            $ret = $Un->assertNotInArray("100",array("100","101","103"), "Test");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertNotInArray("100",array("100","101","103"), "Test");
        $this->assertFalse($ret); 
        asserts::setMode(asserts::ThrowExcept);  
     
        try {
            $ret = $Un->assertNotInArray("100", "1002");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertNotInArray("100", array("aaa"=>"100","101a"=>"101"));
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertNotInArray("", "");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertNotInArray("123asd", "");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
    }

    function test_assertKeyNotInArray() {
        $Un = new asserts();
        $ret = $Un->assertKeyNotInArray("100", array("101"=>"100","101a"=>"101a","12"=>"102"), "KeyInArray Test");
        $this->assertEquals(true, $ret);

        $ret = $Un->assertKeyNotInArray("100", array("100","101","103"), "KeyInArray Test");
        $this->assertEquals(true, $ret);

        try {
            $ret = $Un->assertKeyNotInArray("100", "1002", 'aaa');
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertKeyNotInArray("101a", array("bbb"=>"100","101a"=>"101"), 'aaaaaa');
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertKeyNotInArray("", "", 'test');
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertKeyNotInArray("123asd", "");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertKeyNotInArray("101a", array("bbb"=>"100","101a"=>"101"), 'aaaaaa');
        $this->assertFalse($ret);        
    }

    function test_assertAscending() {
        $Un = new asserts();
        $ret = $Un->assertAscending(array("103"=>"100","102"=>"101","101"=>"101","100"=>"103"), "KeyInArray Test");
        $this->assertEquals(true, $ret);

        $Un = new asserts();
        $ret = $Un->assertAscending(array("100","101","101","102","103"), "KeyInArray Test");
        $this->assertEquals(true, $ret);

        $Un = new asserts();
        $ret = $Un->assertAscending(array("103"=>"100","102"=>"100","101"=>"100","100"=>"100"), "KeyInArray Test");
        $this->assertEquals(true, $ret);

        $Un = new asserts();
        $ret = $Un->assertAscending(array("100","100","100","100","100"), "KeyInArray Test");
        $this->assertEquals(true, $ret);


        $ret = $Un->assertAscending(array(), "KeyInArray Test");
        $this->assertEquals(true, $ret);
        
        try {
            $ret = $Un->assertAscending(array("100", "101","101","100","102","103"), 'aaa');
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertAscending(array("103"=>"100","102"=>"101","101"=>"101","100"=>"100","101a"=>"101"));
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertAscending(array("105", "104","103","102","102","101"));
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertAscending(array("103"=>"105","102"=>"104","101"=>"103","100"=>"102","101a"=>"101"));
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertAscending(array("103", "104","","102","102","101"));
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertAscending(array("103"=>"103","102"=>"104","101"=>"","100"=>"102","101a"=>"101"));
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertAscending("");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertAscending("123asd");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        
        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertAscending(array("103"=>"103","102"=>"104","101"=>"","100"=>"102","101a"=>"101"));
        $this->assertFalse($ret);        
    }

    function test_assertKeyAscending() {
        
        $Un = new asserts();
        $ret = $Un->assertKeyAscending(array("1"=>"10","2"=>"9","3"=>"8","4"=>"7"), "KeyInArray Test");
        $this->assertEquals(true, $ret);
        $ret = $Un->assertKeyAscending(array());
        $this->assertEquals(true, $ret);

        try {
            $ret = $Un->assertKeyAscending("123asd");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        
        try {
            $ret = $Un->assertKeyAscending(array("11"=>"10","2"=>"9","3"=>"8","4"=>"7"), 'aa');
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        
        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertKeyAscending(array("11"=>"10","2"=>"9","3"=>"8","4"=>"7"), 'aa');
        $this->assertFalse($ret);
    
    }
    function test_assertKeyDescending() {
        $Un = new asserts();
        $ret = $Un->assertDescending(array("100"=>"105","101"=>"104","102"=>"103","103"=>"102"), "KeyInArray Test");
        $this->assertEquals(true, $ret);

        $ret = $Un->assertDescending(array("105","104","103","102","101"), "KeyInArray Test");
        $this->assertEquals(true, $ret);

        $ret = $Un->assertKeyDescending(array("103"=>"100","102"=>"100","101"=>"100","100"=>"100"), "KeyInArray Test");
        $this->assertEquals(true, $ret);
        
        $ret = $Un->assertDescending(array("103"=>"100","102"=>"100","101"=>"100","100"=>"100"), "KeyInArray Test");
        $this->assertEquals(true, $ret);

        $ret = $Un->assertDescending(array("100","100","100","100","100"), "KeyInArray Test");
        $this->assertEquals(true, $ret);
        
        $ret = $Un->assertDescending(array(), "KeyInArray Test");
        $this->assertEquals(true, $ret);

        $ret = $Un->assertKeyDescending(array(), "KeyInArray Test");
        $this->assertEquals(true, $ret);
        try {
            $ret = $Un->assertDescending(array("105", "104","103","103","104","105"), 'test');
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertDescending(array("105", "104","103","103","104","105"), 'test');
        $this->assertFalse($ret);
        asserts::setMode(10);

        try {
            $ret = $Un->assertKeyDescending(array("103"=>"100","102"=>"101","101"=>"102","100"=>"102","101a"=>"101"));
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertKeyDescending(array("100", "101","102","102","101","104"));
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertKeyDescending(array("103"=>"101","102"=>"102","101"=>"103","100"=>"104","101a"=>"105"));
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertKeyDescending(array("102", "101","","102","101","104"));
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertKeyDescending(array("103"=>"102","102"=>"101","101"=>"","100"=>"104","101a"=>"105"), 'Test');
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertKeyDescending("");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        
        try {
            $ret = $Un->assertDescending("");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertKeyDescending("123asd");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        
        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertKeyDescending(array("103"=>"102","102"=>"101","101"=>"","100"=>"104","101a"=>"105"));
        $this->assertFalse($ret);
        $ret = $Un->assertKeyDescending(array("103"=>"102","102"=>"101","101"=>"","100"=>"104","101a"=>"105"), "test");
        $this->assertFalse($ret);
        $this->assertEquals(0, "0");
        //$this->assertEquals(0, "a");
        $this->assertEquals(null, false);
        $this->assertEquals(array('a'=>'b', 'c'), array('c', 'a'=>'b'));
    }
    
    function test_assertRegExp() {
        $Un = new asserts();
        $ret = $Un->assertRegExp("/abc/", "abc", "KeyInArray Test");
        $this->assertEquals(true, $ret);

        try {
            $ret = $Un->assertRegExp(Array(),"abc", "KeyInArray Test");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        
        try {
            $ret = $Un->assertRegExp("/abc/",Array(), "KeyInArray Test");
            $this->assertTrue(false);
        }catch(InputError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        
        try {
            $ret = $Un->assertRegExp("/bbc/", "abc", "KeyInArray Test");
            $this->assertTrue(false);
        }catch(AssertError $e) {
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        
        asserts::setMode(asserts::PrintError);
        $ret = $Un->assertRegExp("/bbc/", "abc", "KeyInArray Test");
        $this->assertFalse($ret);
        asserts::setMode(10);
    }

    function test_assertEqualArray() {
        $Un = new asserts();

        try {
            $ret = $Un->assertEqualArray("/bbc/", "abc", "KeyInArray Test");
            $this->assertTrue(false);
        } catch (InputError $e) {
            $this->assertTrue(true);
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        try {
            $ret = $Un->assertEqualArray("/bbc/", array(), "KeyInArray Test");
            $this->assertTrue(false);
        } catch (InputError $e) {
            $this->assertTrue(true);
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        $ret = $Un->assertEqualArray(array("aaa", "a"=>"dda"), array("a"=>"dda", "aaa"), "assertEqualArray Test");
        $this->assertTrue($ret);

        $ret = asserts::assertEqualArray(array("aaa", "a"=>"dda"), array("a"=>"dda", "aaa"), "assertEqualArray Test");
        $this->assertTrue($ret);

        $ret = asserts::assertEqualArray(array("aaa", "b"=>"dda"), array("a"=>"dda", "aaa"), "assertEqualArray Test");
        $this->assertTrue($ret);
        
        try {
            $ret = asserts::assertEqualArray(array("aa", "b"=>"dda"), array("a"=>"dda", "aaa"), "assertEqualArray Test");
            $this->assertTrue($ret);
        } catch (AssertError $e) {
            $this->assertTrue(true);
        }

        asserts::setMode(asserts::PrintError);
        $ret =  asserts::assertEqualArray(array("aa", "b"=>"dda"), array("a"=>"dda", "aaa"), "assertEqualArray Test");
        $this->assertFalse($ret);
        asserts::setMode(10);
        
    }

    function test_assertEqualArrayAssoc() {
        $Un = new asserts();

        try {
            $ret = $Un->assertEqualArrayAssoc("/bbc/", "abc", "KeyInArray Test");
            $this->assertTrue(false);
        } catch (InputError $e) {
            $this->assertTrue(true);
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }
        try {
            $ret = $Un->assertEqualArrayAssoc("/bbc/", array(), "KeyInArray Test");
            $this->assertTrue(false);
        } catch (InputError $e) {
            $this->assertTrue(true);
            $e ->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getCode());
        }

        $ret = $Un->assertEqualArrayAssoc(array("aaa", "a"=>"dda"), array("a"=>"dda", "aaa"), "assertEqualArray Test");
        $this->assertTrue($ret);

        $ret = asserts::assertEqualArrayAssoc(array("aaa", "a"=>"dda"), array("a"=>"dda", "aaa"), "assertEqualArray Test");
        $this->assertTrue($ret);

        try {
            $ret = asserts::assertEqualArrayAssoc(array("aa", "b"=>"dda"), array("a"=>"dda", "aaa"), "assertEqualArray Test");
            $this->assertTrue(false);
        } catch (AssertError $e) {
            $this->assertTrue(true);
        }

        asserts::setMode(asserts::PrintError);
        $ret =  asserts::assertEqualArrayAssoc(array("aa", "a"=>"dda"), array("a"=>"dda", "aaa"), "assertEqualArray Test");
        $this->assertFalse($ret);
        asserts::setMode(10);
        
    }

}
?>
