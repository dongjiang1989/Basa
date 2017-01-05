<?php
require_once(dirname(__FILE__)."/kfcDaemon.php");

logging::debug(posix_getpid());
$a = new kfcDaemon("test", "apple", "/tmp/agt.sock");
logging::debug(posix_getpid());

$a->onMessage = function($msg, &$buf){
    logging::error($msg);
    $buf = "adffadsdfasdf";
};

$a->runAll();
sleep(10);
logging::error("aaaaa", posix_getpid());

$a->onMessage = function($msg, &$buf){
    logging::error($msg);
    $buf = "aaaaaaaaaaa";
};

$a->reloadAll();
sleep(10);
logging::error("bbbbb", posix_getpid());

$a->stopAll();

?>
