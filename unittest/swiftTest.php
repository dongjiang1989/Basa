<?php
declare(encoding='UTF-8');
require_once (dirname(__FILE__)."/../src/util/protocol/swift.php");
class SwiftTest extends PHPUnit_Framework_TestCase {

    public $a;

    protected function setUp() {
        $this->a = new swift\Swift("swiftToolkit", "zfs://127.0.0.1:2181/swift_home");
    }

    function test_send() {
    }

    function tearDown() {
        unset($this->a);
    }

}
