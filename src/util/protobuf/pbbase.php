<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../../com/util.php");
require_once(dirname(__FILE__)."/../../com/Eobject.php");
require_once(dirname(__FILE__).'/../../tools/protobuf/parser/pb_parser.php');

class ProtofileErr extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }
    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ .": [ret:{$this->code}]: {$this->message}\n";
    }
}

class pbBase extends EObject {

    private $fullname;
    private $pathname;
    private $basename;
    private $topath;

    private $parsefile = null; //保存pb_proto_xxx.php文件

    public function __construct($protofile, $host="127.0.0.1", $topath="/tmp/") {
        parent::__construct($host);
        $this->fullname = (realpath(dirname($protofile))=="") ? 
                          ( (realpath(dirname(getcwd()."/".$protofile))=="") ?
                            $protofile :
                            realpath(dirname(getcwd()."/".$protofile))."/".basename($protofile)
                          ) : 
                          realpath(dirname($protofile))."/".basename($protofile);
        $this->pathname = dirname($this->fullname);
        $this->basename = basename($this->fullname);
        $this->topath = $topath;
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
     *  Parser文件是否正常
     * @return boolean
     * @author dongjiang.dongj
     */
    public function parser() {
        $_parser = new PBParser();
        try {
            $_parser->parse($this->fullname, $this->topath);
        } catch (Exception $e) {
            logging::error($this->fullname, "proto file is parse error! error:", $e->getMessage());
            throw new ProtofileErr("proto file is parse error! Error: ".(string)$e->getMessage(), RETTYPE::ERR);
        }

        $name = explode('.', $this->basename);
    
        $ret = $this->run("ls ".$this->topath."/pb_proto_".$name[0].".php", false);
        if ($ret[0] == 0 && count($ret[1]) == 1) {
            $this->parsefile = rtrim($this->topath, "/")."/pb_proto_".$name[0].".php";
            return true;
        } else {
            return false;
        }
    }

    /**
    *  获得parsefile
    * @return string
    * @author dongjiang.dongj
    */
    public function getProtofile() {
        if ($this->parsefile === null) {
            $ret = $this->parser();
            if ($ret == false) {
                throw new ProtofileErr("proto file is parse error! Error: ".$e->getMessage(), RETTYPE::ERR);
            }
        }
        return $this->parsefile;
    }

    public function __destruct() {
        //TODO
        parent::__destruct();
    }
}

?>
