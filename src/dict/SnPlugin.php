<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/DictIPlugin.php");
require_once(dirname(__FILE__)."/../com/file.php");
class SnPlugin extends file implements DictIPlugin {
    const MODE = "sn";
    const CLASSNAME = __CLASS__;
    private $ToolPath = Null;
    private $sn;
    private $doc = array();
    private $key;

    public function __construct($filename, $key= "", $host="127.0.0.1", $init_from="", $auto_bak=True,$is_touch=True){
        parent::__construct($filename=$filename, $host=$host, $init_from=$init_from, $auto_bak=$auto_bak,$is_touch=$is_touch);
        $this->ToolPath = realpath(dirname(self::TOOLPATH));

        $this->fix = date("His");
        $this->saveto("/tmp/".$this->basename().".".$this->fix);

        $this->sn = fopen($this->fullname(),"r+");
        $this->key = $key;
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
     * 插入一行记录
     * @param $key string.
     * @param $value string.
     * @return boolean
     * @author 奇刚
     * @warn AbstractInterface异常
     */
    public function set() {
        if (func_num_args() == 2){
            return $this->set2(func_get_arg(0), func_get_arg(1));
        }
        throw new AbstractInterface(__CLASS__."input arges error!", RETTYPE::ERR);
    }

    /**
     * 插入一条k,v记录
     * @param $key string.
     * @param $value string.
     * @return boolean
     * @author 奇刚
     * @warn AbstractInterface异常
     */
    public function set2($key, $value) {
        if($key == $this->key && preg_match($key."/s*=/s*".$value,file_get_contents($this->fullname())))
        {
            throw new AbstractInterface(__CLASS__."key is duplicate", RETTYPE::ERR);
        }
        $this->doc[$key] = $value;
        return true;
    }

    /**
     * 根据key得到value
     * @param String
     * @return String
     * @author 奇刚
     * @warn AbstractInterface异常
     */
    public function get() {
        if (func_num_args() == 1 ){
            if(array_key_exists(func_get_arg(0), $this->doc)){
                return $this->doc[func_get_arg(0)];
            }
            else{
                return "";
            }
        }
        throw new AbstractInterface(__CLASS__."no arges error!", RETTYPE::ERR);
    }

    /**
     * 删除一条key的记录
     * @param $key string.
     * @return bool
     * @author 奇刚
     * @warn AbstractInterface异常
     */
    public function delete() {
        if (func_num_args() == 1 ){
            unset($this->doc[func_get_arg(0)]);
        }
        return True;
    }

    /**
     * 插入一条k,v记录
     * @param $key string.
     * @param $value string.
     * @return boolean
     * @author 奇刚
     * @warn AbstractInterface异常
     */
    public function add() {
        if (func_num_args() == 2){
            return $this->set2(func_get_arg(0), func_get_arg(1));
        }
        throw new AbstractInterface(__CLASS__."input arges error!", RETTYPE::ERR);
    }

    /**
     * 恢复文件
     * @return boolean
     * @author 奇刚
     * @warn AbstractInterface异常
     */
    public function reset() {
        fclose($this->sn);

        $this->run("mv /tmp/".$this->basename().".".$this->fix." ".$this->fullname());
        $this->sn = fopen($this->fullname(), "r+");
        $this->doc = array();
        return True;
    }

    /**
     * 写入文件
     * @return boolean
     * @author 奇刚
     * @warn AbstractInterface异常
     */
    public function update() {
        $docStr = "<doc>\001\n";
        foreach($this->doc as $k => $v){
            $docStr .= $k . "=" . $v ."\001\n";
        }
        $docStr .= "</doc>\001\n";
        if(filesize($this->fullname()>0)){
            fseek($this->sn,-11,SEEK_END);
        }
        else{
            $docStr .= "finished\001\n";
        }
        fwrite($this->sn, $docStr);
        return True;
    }

    public function __destruct() {
        if($this->sn)
            fclose($this->sn);
        parent::__destruct();
    }
}

?>
