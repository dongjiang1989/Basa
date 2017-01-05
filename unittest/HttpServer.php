<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../src/com/util.php");
require_once(dirname(__FILE__)."/../src/com/Daemon/Daemon.php");
require_once(dirname(__FILE__)."/../src/util/protocol/http.php");

        $work = new Daemon("http://10.125.51.188:4443");
        $work->onMessage = function($connection, $data) {
            logging::info($_SERVER['REQUEST_URI']);
            HttpProtocol::header('HTTP/1.1 200');
            return $connection->send('{"aaa":"bbb"}');
        };
        $work->runAll();
        logging::info(http::get("http://10.125.51.188:4443"));

        logging::debug("http://10.125.51.188:4443");
        logging::pause("stop1:"); 
          
        $work->stopAll();
        
?>
