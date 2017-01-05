<?php
require_once(dirname(__FILE__)."/../src/com/logging.php");
function A(){
    return debug_backtrace();
}

class T {
    function aaa() {
        return debug_backtrace();
    }

    function bbb($p1, $p2=1) {
        return A();
    }
}

class TestDebugBacktrace extends PHPUnit_Framework_TestCase  {
    function main() {
        $ret = A();
        $this->assertGreaterThan(2, count($ret));
        $t = new T();
        $this->assertGreaterThan(2, count($t->aaa()));
        $this->assertGreaterThan(2, count($t->bbb($p1=2)));
        return True;
    }

    function test_main1() {
        $ret = $this->main();
        $this->assertEquals(True, $ret);
    }

    public function test_logging() {
        $log = new logging();
        $ret = $log->info('aaa');
        $this->assertEquals(True, $ret);
        $ret = $log->debug('bb', true, false);
        $this->assertEquals(True, $ret);
        $ret = $log->error(array('a', 'b'), array(array()));
        $this->assertEquals(True, $ret);

        //$ret = $log->pause(123, "", null);
        //$this->assertEquals(True, $ret);
        $ret = $log->warn(Array('a'));
        $this->assertEquals(True, $ret);
        $ret = $log->warn(LOGTYPE::INFO);
        $this->assertEquals(True, $ret);
        $ret = $log->warn($log);
        $this->assertEquals(True, $ret);
    }

    public function test_static_logging() {
        $log = new logging();
        $ret = logging::info('aaaaa');
        $this->assertEquals(True, $ret);
        $ret = logging::debug('bb');
        $this->assertEquals(True, $ret);
        $ret = logging::warn(LOGTYPE::INFO);
        $this->assertEquals(True, $ret);
        $ret = logging::warn($log);
        $this->assertEquals(True, $ret);
        $ret = logging::info($log->getLevelType());
        $this->assertEquals(True, $ret);
        $ret = logging::info($log->aaa);
        $this->assertEquals(True, $ret);
        $ret = logging::info($log->aaa=1);
        $this->assertEquals(True, $ret);
        $ret = logging::info($log->aaa, "aa", 1, array(1,23,4));
        $this->assertEquals(True, $ret);
        $ret = logging::info($aaa="bbbb");
        $this->assertEquals(True, $ret);

        $log->vvv = 1;
        $_xx = $log->vvv;
        $this->assertEquals(1, $_xx);
    }
    public function test_setLevel() {
        $log = new logging();
        $log->setLevel(4);
        
        logging::info("LEVELTYPE", $log->getLevelType());
        $this->assertEquals(4, $log->getLevelType());

        $log->setLevel(LOGTYPE::DEBUG);
        $this->assertEquals(16, $log->getLevelType());

        $log->setLevel(3);
        logging::info($log->getLevelType());
        $this->assertEquals(16, $log->getLevelType());

        $log->setLevel('aaa');
        logging::info($log->getLevelType());
        $this->assertEquals(16, $log->getLevelType());

        $log->setLevel(0);
        logging::info($log->getLevelType());
        $this->assertEquals(16, $log->getLevelType());

        $log->setLevel(-1);
        logging::info($log->getLevelType());
        $this->assertEquals(16, $log->getLevelType());
        
    }

    public function test_getBacktrace() {
        $reflection_class = new ReflectionClass("logging");
        $method = $reflection_class->getMethod("_getBacktrace");
        $method->setAccessible(true);
        $log = new logging();

        try {
            $method->invoke($log, "");
            $this->assertTrue(False);
        }
        catch (Exception $e) {
            $this->assertTrue(True);
        }

        $ret = $method->invoke($log, Array(Array(),Array()));
        $this->assertEquals(Array("Null", "Null", "Null", "Null"), $ret);
        
        $ret = $method->invoke($log, Array(Array(),Array(), Array()));
        $this->assertEquals(Array("Null", "Null", "Null", "Null"), $ret);
        
        $ret = $method->invoke($log, Array());
        $this->assertEquals(Array("Null", "Null", "Null", "Null"), $ret);
        
        $ret = $method->invoke($log, Array(Array()));
        $this->assertEquals(Array("Null", "Null", "Null", "Null"), $ret);
        
        $ret = $method->invoke($log, Array(Array("file"=>"test_filename", "class"=>"test_classname", "function"=>"test_functionname", "line"=>"test_linename")));
        $this->assertEquals(Array("test_filename", "test_classname", "test_functionname", "test_linename"), $ret);
    }

    function test_getLogger() {
        $log = new logging();
        $ret = $log->getLogger("");
        $this->assertEquals(False, $ret);
        $ret = $log->getLogger(Null);
        $this->assertEquals(False, $ret);

        exec('rm -rf aaa.log');
        $ret = $log->getLogger("aaa.log");
        $this->assertEquals(True, $ret);
        //unset($log);
        exec('test -f '.getcwd().'/aaa.log', $o, $s);
        $this->assertEquals(0, $s);
        exec('rm -rf aaa.log');
    }
    
    function test_setLevel1() {
        $log = new logging();
        logging::info($log->getLevelType());
        $this->assertEquals(16, $log->getLevelType());
        
        $log->setLevel(4);
        logging::info($log->getLevelType(), $log->getLevelType());
        $aa = $log->getLevelType();
        $this->assertEquals(4, $aa);       
    }

    function test_writerfile() {
        exec('rm -rf aaa.log');
        $log = new logging();
        $aa = Array('test');
        $ret = $log->getLogger("aaa.log", '', null, false, true, Array(Array()), Array(), $aa);
        logging::info("dongjiang Test", '', null, false, true, Array(Array()), Array(), $aa);
        //logging::pause("dongjiang Test", '', null, false, true, Array(Array()), Array(), $aa);
        logging::error("dongjiang Test", '', null, false, true, Array(Array()), Array(), $aa);
        logging::warn("dongjiang Test", '', null, false, true, Array(Array()), Array(), $aa);
        logging::debug("dongjiang Test", '', null, false, true, Array(Array()), Array(), $aa);
        exec('grep "dongjiang" aaa.log', $o, $s);
        $this->assertEquals(0, $s);
        $this->assertGreaterThan(20, strlen($o[0]));
        exec('rm -rf aaa.log');
    }

    function test_logging_handle() {
        $log = new logging();
        $log->setLevel(4);
        //$log1 = new logging();
        logging::info($log->getLevelType());

        $log->info(logging::getLevelType());

        $log->info($log->getLevelType());
        logging::info(logging::getLevelType());

        $log->info($log->getLevelType());

        $this->assertEquals(4, $log->getLevelType());
        $this->assertEquals(4, logging::getLevelType());
        $this->assertEquals(4, $log->getLevelType());

        exec('rm -rf aaaa.log bbbb.log');
        $log->getLogger('aaaa.log');
        logging::info($log->getLevelType());
        $this->assertFileExists('aaaa.log');
        $log->getLogger('bbbb.log');
        logging::info($log->getLevelType());
        $this->assertFileExists('bbbb.log');
        exec('rm -rf aaaa.log bbbb.log');
    }


}
?>
