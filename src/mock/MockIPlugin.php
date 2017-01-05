<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../com/logging.php");
require_once(dirname(__FILE__)."/../com/IPlugin.php");
interface MockIPlugin extends IPlugin {
    public function start();
    public function setName($name);
    public function stop();
    public function callback($func);
    #public function setNetControl();
    #public function resetNetControl():
}
?>
