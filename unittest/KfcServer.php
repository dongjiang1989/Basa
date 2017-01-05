<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../src/com/util.php");
require_once(dirname(__FILE__)."/../src/util/protocol/http.php");
require_once(dirname(__FILE__)."/../src/mock/KfcServerPlugin.php");

class TestKfcServerPlugin extends PHPUnit_Framework_TestCase  {

    public $_mock;

    public $_count=0;

    function setUp() {
        $this->_mock = new KfcServerPlugin("testmock1", 2, "apple", "/tmp/agt.sock");
    }    

    static function getdataforkfc($group, $sock, $input) {
        $ka = kfc_joingroup($group, $sock);
        $res = kfc_sendmsg($ka, $input, KFC_ASYNC, 20000);
        $msg = kfc_recvmsg($ka, KFC_ASYNC, 20000);
        kfc_leavegroup($ka);
        return $msg;
    }

    function test_start() {
        $this->_mock->callback(function($input, &$buf) {
            $buf = "aaaaaaaaaaaaaaaaaaaaa";
        });
        
        $ret = $this->_mock->start();
        $this->assertEquals($ret, true);
        
        $ret = $this->_mock->run("cat /dev/kuafu_alive");
        logging::error($ret);
        #$this->assertEquals(Send::SendRequestBykfc("apple", "/tmp/agt.sock", "input"), 'aaaaaaaaaaaaaaaaaaaaa');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'aaaaaaaaaaaaaaaaaaaaa');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'aaaaaaaaaaaaaaaaaaaaa');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'aaaaaaaaaaaaaaaaaaaaa');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'aaaaaaaaaaaaaaaaaaaaa');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'aaaaaaaaaaaaaaaaaaaaa');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'aaaaaaaaaaaaaaaaaaaaa');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'aaaaaaaaaaaaaaaaaaaaa');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'aaaaaaaaaaaaaaaaaaaaa');

        $this->_mock->callback(function($input, &$buf) {
            $buf = "bbbbbbb";
            logging::debug($input);
        });
        $ret = $this->_mock->reload();
        $this->assertEquals($ret, true);
        $ret = $this->_mock->run("cat /dev/kuafu_alive");
        logging::error($ret);
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'bbbbbbb');

        $ret = $this->_mock->stop();
        $this->assertEquals($ret, true);
    }

    function test_reload() {
        $this->_mock->callback(function($input, &$buf) {
            $buf = "aaaaaaaaaaaaaaaaaaaaa";
            logging::debug($input);
        });
        
        $ret = $this->_mock->start();
        $this->assertEquals($ret, true);
        
        $ret = $this->_mock->run("cat /dev/kuafu_alive");
        logging::error($ret);
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'aaaaaaaaaaaaaaaaaaaaa');
        
        $this->_mock->callback(function($input, &$buf) {
            $buf = "bbbbbbb";
            logging::debug($input);
        });
        $ret = $this->_mock->reload();
        $this->assertEquals($ret, true);
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'bbbbbbb');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'bbbbbbb');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'bbbbbbb');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'bbbbbbb');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'bbbbbbb');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'bbbbbbb');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "input"), 'bbbbbbb');
        
        $ret = $this->_mock->stop();
        $this->assertEquals($ret, true);
        
    }

    function test_server() {
        $this->_mock->callback(function($input, &$buf) {
            if ($input == 1)
                $buf = "aaa";
            else if ($input == 2)
                $buf = "bbb";
            else if ($input == 3)
                $buf = "ccc";
            else
                $buf = "ddd";
        });
        
        $ret = $this->_mock->start();
        $this->assertEquals($ret, true);
        
        $ret = $this->_mock->run("cat /dev/kuafu_alive");
        logging::error($ret);
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "1"), 'aaa');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "3"), 'ccc');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "1"), 'aaa');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "2"), 'bbb');
        $this->assertEquals($this->getdataforkfc("apple", "/tmp/agt.sock", "iadasd"), 'ddd');
        
        $ret = $this->_mock->stop();
        $this->assertEquals($ret, true);
     
    }

    function test_get() {
        $this->assertEquals($this->_mock->getGroup(), "apple");
        $this->assertEquals($this->_mock->getSock(), "/tmp/agt.sock");
    }

    function test_setname() {
        $this->_mock->setName("aaaaa");
        $this->assertEquals("aaaaa", $this->_mock->_mockname );
    }

    function test_other() {
        try {
            $this->assertEquals($this->_mock->aaa(), "");
        } catch (CallFunctionFail $e) {
            $this->assertTrue(true);
        }
    }

    function tearDown() {
        $this->_mock->__destruct();
    }
}
?>
