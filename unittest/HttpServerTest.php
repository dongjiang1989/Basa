<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../src/com/util.php");
require_once(dirname(__FILE__)."/../src/com/Daemon/Daemon.php");
require_once(dirname(__FILE__)."/../src/util/protocol/http.php");
require_once(dirname(__FILE__)."/../src/machine.php");

class TestHttpServer extends PHPUnit_Framework_TestCase  {

    function test_HttpServer() {
        $hostIP = Machine::getIp();
        $work = new Daemon("http://".$hostIP.":3229");
        $work->onMessage = function($connection, $data) {
            logging::info($_SERVER['REQUEST_URI']);
            HttpProtocol::header('HTTP/1.1 200');
            return $connection->close('{"aaa":"bbb"}');
        };
        $work->runAll();
        logging::info(http::get("http://".$hostIP.":3229"));
        $this->assertEquals(http::get("http://".$hostIP.":3229"), '{"aaa":"bbb"}');

        $work->onMessage = function($connection, $data) {
            return $connection->close('{"aaa":"aaa"}');
        };       

        $work->reloadAll();
        logging::info(http::get("http://".$hostIP.":3229"));
        $this->assertEquals(http::get("http://".$hostIP.":3229"), '{"aaa":"bbb"}');

        $work->stopAll();
        logging::info(http::get("http://".$hostIP.":3229"));
        $this->assertEquals(http::get("http://".$hostIP.":3229"), '');
    }

    function test_HttpServer1() {
        $hostIP = Machine::getIp();
        $work = new Daemon("http://".$hostIP.":3229");
        $work->onMessage = function($connection, $data) {
            HttpProtocol::header('HTTP/1.1 200');
            return $connection->send('{"aaa":"bbb"}');
        };
        $work->runAll();
        logging::info(http::get("http://".$hostIP.":3229"));
        $this->assertEquals(http::get("http://".$hostIP.":3229"), '{"aaa":"bbb"}');


        $work1 = new Daemon("http://".$hostIP.":3239");
        $work1->onMessage = function($connection, $data) {
            logging::info($_SERVER['REQUEST_URI']);
            HttpProtocol::header('HTTP/1.1 200');
            return $connection->close('{"aaa":"ccc"}');
        };

        $work1->runAll();
        logging::info(http::get("http://".$hostIP.":3239"));
        $this->assertEquals(http::get("http://".$hostIP.":3239"), '{"aaa":"ccc"}');
        $this->assertEquals(http::get("http://".$hostIP.":3229"), '{"aaa":"bbb"}');

        $work1->stopAll();
        $work->stopAll();
        logging::info(http::get("http://".$hostIP.":3239"));
    }
}
?>
