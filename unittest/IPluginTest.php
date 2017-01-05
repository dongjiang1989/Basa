<?php
require_once(dirname(__FILE__)."/../src/com/IPlugin.php");
class TestIPlugin extends PHPUnit_Framework_TestCase {
    public function test_CallFunctionFail(){
        try {
            throw new CallFunctionFail("AAA", -1);
            $this->assertTrue(False);
        } catch (CallFunctionFail $e) {
            $e->__toString();
            $this->assertEquals(-1, $e->getcode());
        }
    }
}
?>
