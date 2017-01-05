<?php
require_once(dirname(__FILE__)."/loggingTest.php");
class TestLoggingB extends TestDebugBacktrace {
    public function test_abmain1() {
        $this->test_main1();
    }
}
?>
