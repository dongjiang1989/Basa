<?php
require_once(dirname(__FILE__)."/../src/tools/spyc/Spyc.php");
class TestmasterSuite extends PHPUnit_Framework_TestCase {
    function test_master() {
        $ret = Spyc::YAMLLoad(dirname(__FILE__)."/conf/mergerFrame.yaml");
        var_dump($ret);
    }
}
?>
