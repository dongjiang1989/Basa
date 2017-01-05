<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/com/util.php");
class Ustring extends Object {

    /**
    *   Ustring 自定义string处理相关方法
    * @author dongjiang.dongj
    */
    public function __construct() {
        parent::__construct();
        updated_data_TS();
        //TODO
    }

    /** 
    *   将array的key/val拼接成string
    * @param array $arr :  请求的key/value对
    * @return string ： key=value&key1=val1...
    * @warn TypeErr异常出现；如果$arr并不是array类型
    * @author dongjiang.dongj@
    */
    static function build_url_str($arr) {
        if (gettype($arr) != "array") {
            throw new TypeError("type of \$arr is not array! type:".gettype($arr), RETTYPE::ERR);
        } else {
            $ret = "?";
            foreach($arr as $b=>$v) {
                $ret = $ret . $k."=" . $v . "&";
            }
            return rtrim($ret, "&");
        }
    }

    /** 析构方法 */
    public function __destruct(){
        parent::__destruct();
    }
}

?>
