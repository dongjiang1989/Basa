<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/pbbase.php");
require_once(dirname(__FILE__)."/../../com/util.php");

class pb extends pbBase {

    public function __construct($protofile, $host="127.0.0.1", $topath="/tmp/") {
        parent::__construct($protofile=$protofile, $host=$host, $topath=$topath);
        //TODO
        updated_data_TS();   
    }

    /**
     * 方法重载使用到的系统回调方法
     * @author dongjiang.dongj
     */
    public function __call($f, $p) {
        if (method_exists($this, $f.sizeof($p))) {
            return call_user_func_array(array($this, $f.sizeof($p)), $p);
        } else {
            throw new CallFunctionFail("Tried to call unknown method".get_class($this).'::'.$f, RETTYPE::ERR);
        }
    }

    /**
    * 将array中的数据序列号为 pb二进制
    * @param array，输入array
    * @param obj. 保存对象
    * @author dongjiang.dongj
    */
    static public function Array2function($array, $obj) {
        foreach($array as $k => $v) {
            if ( is_array($v) && array_keys($v) == range(0, count($v)-1)) {  // is reapted
                foreach ($v as $_v) {
                    if (is_array($_v)) {                       // reapted & message
                        $_func_tmp = "add_".$k;
                        $tmp_obj = $obj->$_func_tmp();
                        pb::Array2function($_v, $tmp_obj);
                    } else {
                        $_func = "append_".$k;
                        $obj->$_func($_v);
                    }
                }
            } else if (is_array($v) && array_keys($v) != range(0, count($v)-1)) { // inline message
                $_func = "add_".$k;
                $tmp_obj = $obj->$_func();
                pb::Array2function($v, $tmp_obj);
            } else {  // normal type
                $_func = "set_".$k;
                $obj->$_func($v);
            }
        }
    }

    /**
    *  Serialize 将 array 序列化为 pb object （protbuf string）
    * @return string. protobuf string.
    * @param array
    * @warn maybe throw ProtofileErr Exception
    * @author dongjiang.dongj
    */
    static public function Serialize($Arr, $protofile, $classname, $topath="/tmp/") {
        if (is_file($protofile)) {
            $obj = new pb($protofile=$protofile, $host="127.0.0.1", $topath=$topath);
            try {
                $ret = $obj->parser();
                if ($ret == true) {
                    require_once($obj->getProtofile());
                    //TODO
                    if (class_exists($classname)) {
                        $obj = new $classname();
                        pb::Array2function($Arr, $obj);
                        return $obj->SerializeToString();
                    } else {
                        logging::error($classname, " class is not Exist!");
                        throw new ClassNotExist($classname." class is not Exist!", RETTYPE::ERR);
                    }
                } else {
                    logging::error("Can not find ".$topath."/pb_proto_*.php file !!!");
                    throw new ProtofileErr("Can not find ".$topath."/pb_proto_*.php file !!!", RETTYPE::ERR);
                }
            } catch (ProtofileErr $e) {
                logging::error("Parse proto file error! protofile:", $protofile);
                throw new ProtofileErr($protofile." parse fail !!! error: ".(string)$e->getMessage(), RETTYPE::ERR);
            }
        } else {
            logging::error("Can not find proto file! protofile:", $protofile);
            throw new ProtofileErr("Can not find proto file! protofile:".$protofile, RETTYPE::ERR);
        }

        return null;
    }

    /**
    * 将obj解析成array
    * @param obj. 输入object对象
    * @return $ret 保存在引用中
    * @author dongjiang.dongj@
    */
    static public function obj2array($obj, &$ret) {
        $name_type = $obj->names_type;
        foreach($obj->values as $k => $v) {
            if (gettype($v) == 'object') {
                if (gettype($v->value) == 'object') {
                    $_tmp_ret = array();
                    pb::obj2array($v, $_tmp_ret);
                    $ret[$name_type[$k]] = $_tmp_ret;
                } else {
                    $ret[$name_type[$k]] = $v->value;
                }
            } else if (gettype($v) == 'array' && $v != array()) {
                $_tmp = array();
                foreach($v as $_k=>$_v) {
                    if (gettype($_v) == 'object' && gettype($_v->value) == "object") {
                        $_tmp_ret = array();
                        pb::obj2array($_v, $_tmp_ret);
                        $_tmp[] = $_tmp_ret;
                    } else {
                        $_tmp[] = $_v->value;
                    }
                }
                $ret[$name_type[$k]] = $_tmp;
            } else {
            }
        }
    }

    /**
    *  deserialize 将pb obj反序列化为 array
    * @param string. protobuf string.
    * @return array
    * @warn maybe throw ProtofileErr Exception
    * @author dongjiang.dongj
    */
    static public function Deserialize($string, $protofile, $classname, $topath="/tmp/") {
        if (is_file($protofile)) {
            $obj = new pb($protofile=$protofile, $host="127.0.0.1", $topath=$topath);
            try {
                $ret = $obj->parser();
                if ($ret == true) {
                    require_once($obj->getProtofile());
                    //TODO
                    if (class_exists($classname)) {
                        $obj = new $classname();
                        $obj->parseFromString($string);
                        $ret = array();
                        pb::obj2array($obj, $ret);
                        return $ret;
                    } else {
                        logging::error($classname." class is not Exist!");
                        throw new ClassNotExist($classname." class is not Exist!", RETTYPE::ERR);
                    }
                } else {
                    throw new ProtofileErr("Can not find ".$topath."/pb_proto_*.php file !!!", RETTYPE::ERR);
                }
            } catch (ProtofileErr $e) {
                throw new ProtofileErr($protofile." parse fail !!!", RETTYPE::ERR);
            }
        } else {
            logging::error("Can not find proto file! protofile:", $protofile);
            throw new ProtofileErr("Can not find proto file! protofile:".$protofile, RETTYPE::ERR);
        }

        return null;
        
    }

    /**
    *  serialize 将json 序列化为 protobuf string
    * @param 输入json string
    * @return string. protobuf string.
    * @warn maybe throw TypeError or ProtofileErr Exception
    * @author dongjiang.dongj
    */
    static public function SerializeFromJson($json, $protofile, $classname, $topath="/tmp/") {
        $arr = json_decode($json, true, 16); //TODO: json 解析 depth=16的json数据，效率考虑
        if ($arr === null) {
            logging::error("json decode error! please check json data:", $json);
            throw new TypeError("json decode error! please check json data:".$json, RETTYPE::ERR);
        }

        return pb::Serialize($Arr=$arr, $protofile=$protofile, $classname=$classname, $topath=$topath);
    }


    /**
    *  deserialize 将pb obj反序列化为 json string
    * @param string. protobuf string.
    * @return json string
    * @warn maybe throw TypeError or ProtofileErr Exception
    * @author dongjiang.dongj
    */
    static public function DeserializeToJson($string, $protofile, $classname, $topath="/tmp/") {
        $arr = null;
        try {
            $arr = pb::Deserialize($string=$string, $protofile=$protofile, $classname=$classname, $topath=$topath);
        } catch (Exception $e) {
            logging::error("Protobuf Deserialize error!");
            throw new ProtofileErr("Protobuf Deserialize error!", RETTYPE::ERR);
        }
        if ($arr != null) {
            $string = json_encode($arr);
            if ($string === FALSE) {
                logging::error("json encode error! please check array data:", $arr);
                throw new TypeError("json encode error! please check array data:".print_r($arr, true), RETTYPE::ERR);
            }
            return $string;
        }
        return null;
    }
    /**
    *  析构方法
    * @author dongjiang.dongj
    */
    public function __destruct() {
        //TODO
        parent::__destruct();
    }
}

?>
