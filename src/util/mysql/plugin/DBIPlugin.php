<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../../../com/logging.php");
require_once(dirname(__FILE__)."/../../../com/IPlugin.php");
interface DBIPlugin extends IPlugin {
    
    public function set_db();
    public function set_table();
    public function set_field();

    public function get_db();
    public function get_table();
    public function get_field();

    public function delete_db();
    public function delete_table();
    public function delete_field();

}
?>
