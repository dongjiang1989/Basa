<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../com/logging.php");
require_once(dirname(__FILE__)."/../com/IPlugin.php");
interface ConfIPlugin extends IPlugin {
    public function has_key();
    public function set();
    public function get();
    public function delete();
    public function add();
    public function iset();
    public function isChange();
}
?>
