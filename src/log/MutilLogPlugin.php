<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/LogIPlugin.php");
require_once(dirname(__FILE__)."/SingleLogPlugin.php");

class MutilLogPlugin extends SingleLogPlugin implements LogIPlugin {
    const MODE = "Mutil";
    const CLASSNAME = __CLASS__;
    public function __construct($filename, $host="127.0.0.1", $init_from="", $auto_bak=False, $is_touch = True){
        parent::__construct($filename=$filename, $host=$host, $init_from=$init_from, $auto_bak=$auto_bak, $is_touch=$is_touch);
        //TODO
    }
    
    public function __call($f, $p) {
        if (method_exists($this, $f.sizeof($p))) {
            return call_user_func_array(array($this, $f.sizeof($p)), $p);
        } else {
            throw new CallFunctionFail("Tried to call unknown method".get_class($this).'::'.$f, RETTYPE::ERR);
        }
    }

    public function getValue() {
    }
    
    public function getValue2($key, $value) {
        return array($key=>$value);
    }
    
    public function get() {
    }

    public function line() {
    }

    public function seek() {
    }

    public function isexist() {
    }

    public function isroll() {
    }

    public function search() {
    }

    public function __destruct() {
        //TODO
        parent::__destruct();
    }
}
?>
