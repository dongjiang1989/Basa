<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/DictIPlugin.php");
require_once(dirname(__FILE__)."/../com/file.php");
class TdbmPlugin extends file implements DictIPlugin {
    const MODE = "tdbm";
    const CLASSNAME = __CLASS__;
    private $ToolPath = Null;
    private $db;

    private $fix;
    private $is_bak = False;

    public function __construct($filename, $host="127.0.0.1", $init_from="", $auto_bak=True, $is_touch=False){
        parent::__construct($filename=$filename, $host=$host, $init_from=$init_from, $auto_bak=$auto_bak, $is_touch=$is_touch);

        $this->fix = date("His");
        if(file_exists($this->fullname())){
            $this->saveto("/tmp/".$this->basename().".".$this->fix);
            $this->is_bak = True;
        }
        $this->db = tdbm_popen( $this->fullname(), O_RDWR|O_CREAT, 0666, 0, 0 );

        if ( !is_resource($this->db) ) {
            throw new InitFail("open tdbm file fail!");
        }
        tdbm_lock( $this->db );
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
     * 插入一条k,v记录
     * @param $key string.
     * @param $value string.
     * @return boolean
     * @author qigang.llb
     * @warn AbstractInterface??
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
     * @author qigang
     * @warn AbstractInterface??
     */
    public function set2($key, $value) {
        $status = tdbm_store($this->db, $key, $value, TDBM_REPLACE);
        if ( $status == -1 ) {
            throw new AbstractInterface(__CLASS__."tdbm set fail!", RETTYPE::ERR);
        }
        return true;
    }

    /**
     * 根据key获取结果
     * @param $key string.
     * @return string
     * @author qigang
     * @warn AbstractInterface??
     */
    public function get() {
        if (func_num_args() == 1 ){
            return tdbm_fetch( $this->db, func_get_arg(0) );
        }
        throw new AbstractInterface(__CLASS__."no arges error!", RETTYPE::ERR);
    }

    /**
     * 删除一条key的记录
     * @param $key string.
     * @return bool
     * @author qigang
     * @warn AbstractInterface??
     */
    public function delete() {
        if (func_num_args() == 1 ){
            return tdbm_delete($this->db,func_get_arg(0));
        }
        throw new AbstractInterface(__CLASS__."no arges error!", RETTYPE::ERR);
    }

    /**
     * 插入一条k,v记录
     * @param $key string.
     * @param $value string.
     * @return boolean
     * @author qigang
     * @warn AbstractInterface??
     */
    public function add() {
        if (func_num_args() == 2){
            return $this->set2(func_get_arg(0), func_get_arg(1));
        }
        throw new AbstractInterface(__CLASS__."input arges error!", RETTYPE::ERR);
    }

    /**
     * 回复文件
     * @return boolean
     * @author qigang
     * @warn AbstractInterface
     */
    public function reset() {
        if($this->is_bak){
            $this->run("mv /tmp/".$this->basename().".".$this->fix." ".$this->fullname());
            $this->db = tdbm_popen( $this->fullname(), O_RDWR|O_CREAT, 0666, 0, 0 );
            if ( !is_resource($this->db) ) {
                throw new InitFail("open tdbm file fail!");
            }
            tdbm_lock( $this->db );
        }
        else{
            $this->remove();
            $this->db = tdbm_popen( $this->fullname(), O_RDWR|O_CREAT, 0666, 0, 0 );
            if ( !is_resource($this->db) ) {
                throw new InitFail("open tdbm file fail!");
            }
            tdbm_lock( $this->db );
        }
        return True;
    }

    public function update() {
        return True;
    }

    public function __destruct() {
        $this->run("rm -rf /tmp/".$this->basename().".".$this->fix );
        parent::__destruct();
    }
}

?>
