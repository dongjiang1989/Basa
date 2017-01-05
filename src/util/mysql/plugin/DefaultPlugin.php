<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../../../com/util.php");
require_once(dirname(__FILE__)."/DBIPlugin.php");
class DefaultPlugin extends mysqlBase implements DBIPlugin {

    private $_DBData = array(); //表接口数据
    
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
