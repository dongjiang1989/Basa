<?php
declare(encoding='UTF-8');
require_once (dirname(__FILE__)."/../src/uarray.php");
class Testuarray extends PHPUnit_Framework_TestCase {

    protected $__a;

    protected function setUp() {
        $this->__a = new Uarray();
    }

    function test_getArrValuebyKey() {
        try {
            $ret = Uarray::getArrValuebyKey("","","");
            logging::debug($ret);
            $this->assertEquals(false, true);
        } catch (TypeError $e) {
            $this->assertEquals(True, true);
        }
        $ret = Uarray::getArrValuebyKey(array(),"","aaaa");
        logging::debug($ret);
        $this->assertEquals($ret, "aaaa");
        $ret = Uarray::getArrValuebyKey(array("a"=>"b"),"","aaa");
        logging::debug($ret);
        $this->assertEquals($ret, "aaa");
        $ret = Uarray::getArrValuebyKey(array("a"=>"b"),"a","aaaa");
        logging::debug($ret);
        $this->assertEquals($ret, "b");
    }

    function test_getArraybyPreKey() {
        try {
            $ret = Uarray::getArraybyPreKey("","");
            logging::debug($ret);
            $this->assertEquals(false, true);
        } catch (TypeError $e) {
            $this->assertEquals(True, true);
        }

        $ret = Uarray::getArraybyPreKey(array("aa"),"","");
        logging::debug($ret);
        $this->assertEquals($ret, array("aa"));

        $ret = Uarray::getArraybyPreKey(array("aa"),"0","");
        logging::debug($ret);
        $this->assertEquals($ret, array("aa"));

        $ret = Uarray::getArraybyPreKey(array("aa"),"1","");
        logging::debug($ret);
        $this->assertEquals($ret, array());

    }

    function test_array_diff_recursive() {
        try {
            $ret = Uarray::array_diff_recursive("", "");
            $this->assertEquals(false, true);
        } catch (TypeError $e) {
            $this->assertEquals(True, true);
        }
        
        $ret = Uarray::array_diff_recursive(array("a"=>"aa"), array("b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array(), $ret);
        
        $ret = Uarray::array_diff_recursive(array("a"=>"aa", "bb"), array("bb", "b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array(), $ret);
        
        $ret = Uarray::array_diff_recursive(array("a"=>"aa", "bb", array("")), array("bb", "b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array(array("")), $ret);
        
        $ret = Uarray::array_diff_recursive(array("a"=>"aa", "bb", array("")), array(array("da"), "bb", "b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array(array("")), $ret);
    
        $ret = Uarray::array_diff_recursive(array("a"=>"aa", "bb", array("","da")), array(array("da", "ddd"), "bb", "b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array(array("")), $ret);
        
        $ret = Uarray::array_diff_recursive(array(array("0"),"a"=>"aa", "bb", array("","da"), array("1")), array(array("da", "ddd"), "bb", "b"=>"aa", array("0")));
        logging::debug($ret);
        $this->assertEquals(array(array(""), array("1")), $ret);
    }

    
    function test_array_diff_assoc_recursive() {
        try {
            $ret = Uarray::array_diff_assoc_recursive("", "");
            $this->assertEquals(false, true);
        } catch (TypeError $e) {
            $this->assertEquals(True, true);
        }
        
        $ret = Uarray::array_diff_assoc_recursive(array("a"=>"aa"), array("b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array("a"=>"aa"), $ret);
        
        $ret = Uarray::array_diff_assoc_recursive(array("b"=>"aa", "bb"), array("bb", "b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array(), $ret);
        
        $ret = Uarray::array_diff_assoc_recursive(array("b"=>"aa", "bb", array("d"=>1, "ddd")), array("bb", "b"=>"aa", array("ddd", "dd"=>"dae")));
        logging::debug($ret);
        $this->assertEquals(array("1"=>array("d"=>"1")), $ret);
        
        $ret = Uarray::array_diff_assoc_recursive(array("b"=>"aa", "bb","cccc"=>"cccc", array("d"=>1, "ddd"), "a"=>array(), "cc"=>array()), array("bb", "b"=>"aa", array("ddd", "dd"=>"dae"), "cc"=>"cc"));
        logging::debug($ret);
        $this->assertEquals(array("1"=>array("d"=>"1"), "a"=>array(), "cccc"=>"cccc", "cc"=>array()), $ret);
    }

    function test_SubArray() {
        $arr = array("aaa", "bb");
        $ret = Uarray::SubArray($arr, "aaa....bbb", ".");
        $this->assertEquals(null, $ret);
        
        $ret = Uarray::SubArray($arr, "aaa....bbb", "a");
        $this->assertEquals(null, $ret);
        
        $ret = Uarray::SubArray($arr, "", "a");
        $this->assertEquals($arr, $ret);

        $a = array("a"=>array("aa", "bb"));
        $ret = Uarray::SubArray($a, "ab0", "b");
        $this->assertEquals("aa", $ret);

        try {
            $ret = Uarray::SubArray("aa", "ab0", "b");
            $this->assertTrue(false);
        } catch (TypeError $e) {
            $this->assertTrue(true);
        }
    }

    function test_ParseStringToArray() {
        $ret = Uarray::ParseStringToArray("aaba,aaaaba,aaba.aabaaaa,aaa,aab", array(".", ",", "b"));
        logging::debug($ret);
        $this->assertEquals(array(array(array("aa", "a"),array("aaaa", "a"),array("aa", "a")), array(array("aa", "aaaa"), array("aaa"), array("aa", ""))), $ret);
        
        $ret = Uarray::ParseStringToArray("aaa", array());
        $this->assertEquals(array("aaa"), $ret);

        $ret = Uarray::ParseStringToArray("", array("aaaa"));
        $this->assertEquals(array(""), $ret);
        
        $ret = Uarray::ParseStringToArray("aaaabaaaa", array("aaaa"));
        $this->assertEquals(array("", "b", ""), $ret);

        try {
            $ret = Uarray::SubArray("aa", "ab0");
            $this->assertTrue(false);
        } catch (TypeError $e) {
            $this->assertTrue(true);
        }
    }

    function tearDown() {
        unset($this->__a);
    }

}
