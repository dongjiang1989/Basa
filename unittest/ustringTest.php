<?php
declare(encoding='UTF-8');
require_once (dirname(__FILE__)."/../src/uarray.php");
class Testustring extends PHPUnit_Framework_TestCase {

    protected $__a;

    protected function setUp() {
        $this->__a = new Ustring();
    }

    function test_getArrValuebyKey() {
        try {
            $ret = Ustring::getArrValuebyKey("","","");
            logging::debug($ret);
            $this->assertEquals(false, true);
        } catch (TypeError $e) {
            $this->assertEquals(True, true);
        }
        $ret = Ustring::getArrValuebyKey(array(),"","aaaa");
        logging::debug($ret);
        $this->assertEquals($ret, "aaaa");
        $ret = Ustring::getArrValuebyKey(array("a"=>"b"),"","aaa");
        logging::debug($ret);
        $this->assertEquals($ret, "aaa");
        $ret = Ustring::getArrValuebyKey(array("a"=>"b"),"a","aaaa");
        logging::debug($ret);
        $this->assertEquals($ret, "b");
    }

    function test_getArraybyPreKey() {
        try {
            $ret = Ustring::getArraybyPreKey("","");
            logging::debug($ret);
            $this->assertEquals(false, true);
        } catch (TypeError $e) {
            $this->assertEquals(True, true);
        }

        $ret = Ustring::getArraybyPreKey(array("aa"),"","");
        logging::debug($ret);
        $this->assertEquals($ret, array("aa"));

        $ret = Ustring::getArraybyPreKey(array("aa"),"0","");
        logging::debug($ret);
        $this->assertEquals($ret, array("aa"));

        $ret = Ustring::getArraybyPreKey(array("aa"),"1","");
        logging::debug($ret);
        $this->assertEquals($ret, array());

    }

    function test_array_diff_recursive() {
        try {
            $ret = Ustring::array_diff_recursive("", "");
            $this->assertEquals(false, true);
        } catch (TypeError $e) {
            $this->assertEquals(True, true);
        }
        
        $ret = Ustring::array_diff_recursive(array("a"=>"aa"), array("b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array(), $ret);
        
        $ret = Ustring::array_diff_recursive(array("a"=>"aa", "bb"), array("bb", "b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array(), $ret);
        
        $ret = Ustring::array_diff_recursive(array("a"=>"aa", "bb", array("")), array("bb", "b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array(array("")), $ret);
        
        $ret = Ustring::array_diff_recursive(array("a"=>"aa", "bb", array("")), array(array("da"), "bb", "b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array(array("")), $ret);
    
        $ret = Ustring::array_diff_recursive(array("a"=>"aa", "bb", array("","da")), array(array("da", "ddd"), "bb", "b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array(array("")), $ret);
        
        $ret = Ustring::array_diff_recursive(array(array("0"),"a"=>"aa", "bb", array("","da"), array("1")), array(array("da", "ddd"), "bb", "b"=>"aa", array("0")));
        logging::debug($ret);
        $this->assertEquals(array(array(""), array("1")), $ret);
    }

    
    function test_array_diff_assoc_recursive() {
        try {
            $ret = Ustring::array_diff_assoc_recursive("", "");
            $this->assertEquals(false, true);
        } catch (TypeError $e) {
            $this->assertEquals(True, true);
        }
        
        $ret = Ustring::array_diff_assoc_recursive(array("a"=>"aa"), array("b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array("a"=>"aa"), $ret);
        
        $ret = Ustring::array_diff_assoc_recursive(array("b"=>"aa", "bb"), array("bb", "b"=>"aa"));
        logging::debug($ret);
        $this->assertEquals(array(), $ret);
        
        $ret = Ustring::array_diff_assoc_recursive(array("b"=>"aa", "bb", array("d"=>1, "ddd")), array("bb", "b"=>"aa", array("ddd", "dd"=>"dae")));
        logging::debug($ret);
        $this->assertEquals(array("1"=>array("d"=>"1")), $ret);
        
        $ret = Ustring::array_diff_assoc_recursive(array("b"=>"aa", "bb","cccc"=>"cccc", array("d"=>1, "ddd"), "a"=>array(), "cc"=>array()), array("bb", "b"=>"aa", array("ddd", "dd"=>"dae"), "cc"=>"cc"));
        logging::debug($ret);
        $this->assertEquals(array("1"=>array("d"=>"1"), "a"=>array(), "cccc"=>"cccc", "cc"=>array()), $ret);
    }

    function tearDown() {
        unset($this->__a);
    }

}
