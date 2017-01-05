<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../com/logging.php");
require_once(dirname(__FILE__)."/../com/IPlugin.php");
interface DictIPlugin extends IPlugin {
    const TOOLPATH = "./tools/";
    public function set();
    public function get();
    public function delete();
    public function add();
    public function reset();
    public function update();
}
?>
