<?php
/**
 * User: qigang
 * Date: 14-12-11
 * Time: ÏÂÎç7:48
 * To Test TdbmPlugin
 */

require_once(dirname(__FILE__)."/../src/dictmanager.php");
class TestSnPlugin extends PHPUnit_Framework_TestCase {

    private $filename = "/tmp/sn.xml";

    function setup() {
        $DM = new DictManager();
        $this->sn = $DM->getInstanceHandle('sn', array($this->filename));
    }

    public function test_set(){
        $this->sn->set("k_a","v_a");
        $this->assertEquals("v_a",$this->sn->get("k_a"));
    }

    public function test_delete(){
        $this->sn->set("k_a","v_a");
        $this->sn->delete("k_a");
        $this->assertEquals("",$this->sn->get("k_a"));
    }

    public function test_add(){
        $this->sn->delete("k_a");
        $this->sn->add("k_a","v_a");
        $this->assertEquals("v_a",$this->sn->get("k_a"));
    }

    public function test_reset(){
        $this->assertTrue($this->sn->reset());
    }

    public function test_update(){
        $this->assertTrue($this->sn->update());
    }

    function tearDown() {
        unset($this->sn);
    }

}
