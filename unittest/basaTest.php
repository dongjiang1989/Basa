<?php
require_once(dirname(__FILE__)."/../src/basa.php");
class Testbasa extends PHPUnit_Framework_TestCase {
    public function test_logging(){
        $ret = logging::info("aaaaaa");
        $this->assertTrue($ret);
    }
}
?>

