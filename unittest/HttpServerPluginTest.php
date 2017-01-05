<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../src/com/util.php");
require_once(dirname(__FILE__)."/../src/util/protocol/http.php");
require_once(dirname(__FILE__)."/../src/mock/HttpServerPlugin.php");

class TestHttpServerPlugin extends PHPUnit_Framework_TestCase  {

    public $_mock;

    public $_count=0;

    function setUp() {
        $this->_mock = new HttpServerPlugin("testmock1", 2, "255.12.45.145", "80");
    }    

    function test_start() {
        $this->_mock->callback(function($connection, $data) {
            HttpProtocol::header('HTTP/1.1 200');
            $_count=1;
            return $connection->send('{"aaa":"'.$_count.'"}');
        });

        $ret = $this->_mock->start();
        $this->assertEquals($ret, true);

        $this->assertEquals(http::get($this->_mock->geturl()), '{"aaa":"1"}');
        $this->assertEquals(http::get($this->_mock->geturl()), '{"aaa":"1"}');
        $this->assertEquals(http::get($this->_mock->geturl()), '{"aaa":"1"}');
        $this->assertEquals(http::get($this->_mock->geturl()), '{"aaa":"1"}');

        $ret = $this->_mock->stop();
        $this->assertEquals($ret, true);
        $this->assertEquals(http::get($this->_mock->geturl()), '');
    }

    function test_start1() {
        $connection_count=0;

        $this->_mock->callback( function ($connection, $data) {
            global $connection_count;
            HttpProtocol::header('HTTP/1.1 200');
            $connection_count++;
            logging::debug($data);
            return $connection->send('{"aaa":"'.$connection_count.'"}');
        });

        $ret = $this->_mock->start();
        $this->assertEquals($ret, true);

        $this->assertEquals(http::get($this->_mock->geturl()), '{"aaa":"1"}');
        $this->assertEquals(http::get($this->_mock->geturl()), '{"aaa":"2"}');
        $this->assertEquals(http::get($this->_mock->geturl()), '{"aaa":"3"}');
        $this->assertEquals(http::get($this->_mock->geturl()), '{"aaa":"4"}');

        $ret = $this->_mock->stop();
        $this->assertEquals($ret, true);
        $this->assertEquals(http::get($this->_mock->geturl()), '');
    }
    
    function test_start2() {
        $connection_count=0;

        $this->_mock->callback(function ($connection, $data) {
            global $connection_count;
            HttpProtocol::header('HTTP/1.1 200');
            $connection_count++;
            logging::debug($data);
            if ($data)
            return $connection->send('{"aaa":"'.$connection_count.'"}');
        });

        $ret = $this->_mock->start();
        $this->assertEquals($ret, true);

        $this->assertEquals(http::get($this->_mock->geturl()), '{"aaa":"1"}');
        $this->assertEquals(http::get($this->_mock->geturl()), '{"aaa":"2"}');
        $this->assertEquals(http::get($this->_mock->geturl()), '{"aaa":"3"}');
        $this->assertEquals(http::get($this->_mock->geturl()), '{"aaa":"4"}');

        $ret = $this->_mock->stop();
        $this->assertEquals($ret, true);
        $this->assertEquals(http::get($this->_mock->geturl()), '');
    }

    function test_get() {
        $this->assertTrue($this->_mock->getPort() != 80);
        $this->assertTrue($this->_mock->getDomain() != "127.0.0.2");
    }

    function test_set_name() {
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
        unset($this->_mock);
    }

}
?>
