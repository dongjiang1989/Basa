<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../com/logging.php");
require_once(dirname(__FILE__)."/../com/IPlugin.php");
interface LogIPlugin extends IPlugin {
#    public function getValue();

    public function get();

    public function line();

    public function seek();

    public function isexist();

    public function isroll();

    public function search();

}
?>
