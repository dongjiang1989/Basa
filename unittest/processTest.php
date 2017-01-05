<?php
declare(encoding='UTF-8');
require_once (dirname(__FILE__)."/../src/process.php");
class processTest extends PHPUnit_Framework_TestCase {

    protected $module_ok;
    protected $module_no;

    protected function setUp() {
        $this->module_ok = new Process("java", "/home/dongjiang.dongj/tools" );
        $this->module_no = new Process("aaaaaaaaa", "/home/dongjiang.dongj/tmp", "10.125.51.188", "", false);
    }

/*
    public function test_setcoredumpcontrol() {
        list($s, $o) = $this->module_ok->run("ulimit -c unlimited");
        $ret = $this->module_ok->setcoredumpcontrol();
        $this->assertEquals(True, $ret);

        list($s, $o) = $this->module_ok->run("ulimit -c");
        $this->assertEquals(0, $s);
        $this->assertEquals("unlimited", $o[0]);

        list($s, $o) = $this->module_ok->run("cat /proc/sys/kernel/core_pattern");
        $this->assertEquals("/home/a/search/logs/core.%e_%t", $o[0]);

        logging::info("end~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~");
    }
    
    public function test_setcoredumpcontrol1() {
        $ret = $this->module_ok->setcoredumpcontrol("/tmp/");
        $this->assertEquals(True, $ret);

        list($s, $o) = $this->module_ok->run("ulimit -c");
        $this->assertEquals(0, $s);
        $this->assertEquals("unlimited", $o[0]);

        list($s, $o) = $this->module_ok->run("cat /proc/sys/kernel/core_pattern");
        $this->assertEquals("/tmp/core.%e_%t", $o[0]);
    }

    public function test_cleanCore() {
        $ret = $this->module_ok->cleanCore();
        $this->assertEquals(true, $ret);
    }

*/
    public function test_isCoreDumped() {
        $this->module_ok->run("rm -rf core*");
        $ret = $this->module_ok->setcoredumpcontrol("./");
        $ret = $this->module_ok->isCoreDumped();
        $this->assertEquals(false, $ret);

        $this->module_ok->run("touch core.1_1");
        $ret = $this->module_ok->isCoreDumped();
        $this->assertEquals(true, $ret);
        $this->module_ok->run("rm core.1_1");

        $this->module_ok->run("touch core.1_1.1234");
        $ret = $this->module_ok->isCoreDumped(1234);
        $this->assertEquals(true, $ret);
        $this->module_ok->run("rm core.1_1.1234");
    }

    function test_startstop() {
        $ret = $this->module_ok->start("ls -l");
        $this->assertEquals(0, $ret[0]);

        $ret = $this->module_ok->stop("ls -l");
        $this->assertEquals(0, $ret[0]);
    }

    function test_checkStart_Stop() {
        function aaaaaaaaaaaaaaa() {
            return 10;
        }
        $ret = $this->module_ok->checkStart("aaaaaaaaaaaaaaa");
        $this->assertEquals(10, $ret);

        $ret = $this->module_ok->checkStop("aaaaaaaaaaaaaaa");
        $this->assertEquals(10, $ret);

        $ret = $this->module_ok->reload("ls -l");
        $this->assertEquals(0, $ret[0]);
    }

    function test_gethome() {
        $ret = $this->module_ok->gethome();
        $this->assertEquals("/home/dongjiang.dongj/tools", $ret);
    }

    function test_getIp() {
        $ret = $this->module_ok->getIp();
        $ret1 = gethostbyname(gethostname());
        $this->assertEquals($ret1, $ret);
    }

    function test_getPid() {
        $ret = $this->module_ok->getPid();
        $this->assertTrue($ret[0]>0);

        $ret = $this->module_ok->getPid("sh");
        $this->assertTrue($ret[0]>0);

        $ret = $this->module_ok->getPid("aaaaaaaaaaaaaa");
        $this->assertEquals($ret, array());
    }

    function test_getMem() {
        $ret = $this->module_ok->getMem();
        $this->assertTrue($ret>0);

        $ret = $this->module_ok->getMem("aaaaaaaaaaaaaaaaaaa");
        $this->assertEquals($ret, 0);
    }

    function test_getVmem() {
        $ret = $this->module_ok->getVmem();
        $this->assertTrue($ret>0);
        $ret = $this->module_ok->getVmem("aaaaaaaaaaaaaaaaaaa");
        $this->assertEquals($ret, 0);
    }

    function test_getThreads() {
        $ret = $this->module_ok->getThreads();
        $this->assertTrue($ret>0);

        $ret = $this->module_ok->getThreads("aaaaaaaaaaaaaaaaaaa");
        $this->assertEquals($ret, 0);
    }

    function test_is_Alive() {
        $ret = $this->module_ok->is_Alive();
        $this->assertEquals($ret, True);

        $ret = $this->module_ok->is_Alive("aaaaaaa");
        $this->assertEquals($ret, false);
        
        $ret = $this->module_no->is_Alive("aaaaaaa");
        $this->assertEquals($ret, false);
        
        $ret = $this->module_no->is_Alive();
        $this->assertEquals($ret, false);
    }

    function test_getProc() {
        $ret = $this->module_ok->getProc();
        $this->assertEquals($ret, "java");
    }

    function test_Cpid() {
        $ret = $this->module_ok->getCpid();
        $this->assertTrue(count($ret)>0);
    }


    function tearDown() {
        unset($this->module_ok);
        unset($this->module_no);
    }

}
