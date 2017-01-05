<?php
declare(encoding='UTF-8');
require_once (dirname(__FILE__)."/../src/machine.php");
class machineTest extends PHPUnit_Framework_TestCase {

    protected $m;

    protected function setUp() {
        $this->m = new Machine("java", "/home/dongjiang.dongj/tools" );
    }

    function test_getIp() {
        $ret = Machine::getIp();
        logging::debug($ret);
        $this->assertNotEquals(False, $ret);
        $ret = $this->m->getIp();
        logging::debug($ret);
        $this->assertNotEquals(False, $ret);
    }

    function test_getHostname() {
        $ret = Machine::getHostname();
        logging::debug($ret);
        $this->assertNotEquals(False, $ret);
        $ret = $this->m->getHostname();
        logging::debug($ret);
        $this->assertNotEquals(False, $ret);
    }

    function tearDown() {
        unset($this->m);
    }

}
