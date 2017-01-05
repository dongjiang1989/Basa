<?php
/**
 * User: qigang
 * Date: 14-12-11
 * Time: ÏÂÎç7:48
 * To Test TdbmPlugin
 */

require_once(dirname(__FILE__)."/../src/dictmanager.php");
class TestTdbmPlugin extends PHPUnit_Framework_TestCase {

    private $tdbm;
    private $filename = "/tmp/test.tdbm";

    function setup() {
        $DM = new DictManager();
        $this->tdbm = $DM->getInstanceHandle("tdbm",array($this->filename));
    }
    public function test_set(){
        $this->tdbm->set("k_a","v_a");
        $this->assertEquals("v_a",$this->tdbm->get("k_a"));
    }

    public function test_delete(){
        $this->tdbm->set("k_a","v_a");
        $this->tdbm->delete("k_a");
        $this->assertEquals("",$this->tdbm->get("k_a"));
    }

    public function test_add(){
        $this->tdbm->delete("k_a");
        $this->tdbm->add("k_a","v_a");
        $this->assertEquals("v_a",$this->tdbm->get("k_a"));
    }

    public function test_reset(){
        $this->assertTrue($this->tdbm->reset());
    }

    public function test_update(){
        $this->assertTrue($this->tdbm->update());
    }

}