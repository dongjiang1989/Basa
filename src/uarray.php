<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/com/util.php");
class Uarray extends Object {

    /**
    *   Uarray 自定义array处理相关方法
    * @author dongjiang.dongj
    */
    public function __construct() {
        parent::__construct();
        updated_data_TS();
        //TODO
    }

    /** 
    *   获取数组中对应key的value，没有时返回默认值
    * @param array $arr 要查询的数组 
    * @param string $key 数组中的key 
    * @param string $default  返回的默认值 
    * @return TypeErr异常出现；如果$arr并不是array类型
    * @author dongjiang.dongj@
    */
    static function getArrValuebyKey($arr, $key, $default) {
        if (gettype($arr) != "array") {
            throw new TypeError("type of \$arr is not array! type:".gettype($arr), RETTYPE::ERR);
        } else {
            if ( array_key_exists($key, $arr) == true ) {
                return $arr[$key];
            } else {
                return $default;
            }
        }
    }

    /** 
    *  返回数组中key以特定字符开头的数组内容，并对这些key进行重写生成新的数组 (key中打头的内容去掉)
    * @param array $arr 需要获取内容的数组  
    * @param sting $key  数组key名称前缀内容 
    * @return $array 匹配到的数组内容，key中打头的内容去掉 
    * @author dongjiang.dongj@
    */ 
    static function getArraybyPreKey($arr, $key="") {
        $retArr = array();
        if (gettype($arr) != "array") {
            throw new TypeError("type of $arr is not array! type:".gettype($arr), RETTYPE::ERR);
        } else {
            $cnt = strlen($key);
            foreach($arr as $k => $v) {
                if( substr($k, 0, $cnt) == $key) {
                    $tmpkey = substr($k, $cnt, strlen($k)-$cnt); 
                    $retArr[$tmpkey] = $v;
                }
            }
            return $retArr;
        }
    }

    /**
    * 递归的array_diff_assoc：返回一个数组，该数组包括了所有在 array1 中但是不在任何其它参数数组中的值，且比较键名
    * @param $array1 $array2, 
    * @return array
    * @warn maybe TypeError Exception
    * @author dongjiang.dongj@
    */
    static function array_diff_assoc_recursive($array1, $array2)
    {
        if (gettype($array1) != "array" || gettype($array2) != "array") {
            throw new TypeError("type of import is not array! type:".gettype($array1)." ".gettype($array2), RETTYPE::ERR);
        } else {
            $difference = array();
            foreach($array1 as $key => $value)
            {   
                if(is_array($value))
                {   
                    if(!array_key_exists($key, $array2))
                    {   
                        $difference[$key] = $value;
                    }   
                    else if(!is_array($array2[$key]))
                    {   
                        $difference[$key] = $value;
                    }   
                    else
                    {   
                        $new_diff = Uarray::array_diff_assoc_recursive($value, $array2[$key]);
                        if($new_diff != array())
                        {   
                            $difference[$key] = $new_diff;
                        }   
                    }   
                }   
                else if(!array_key_exists($key, $array2) || $array2[$key] != $value)
                {   
                    $difference[$key] = $value;
                }   
            }   
            return !isset($difference) ? array() : $difference;
        }
    }

    /**
    * 递归的array_diff：返回一个数组，该数组包括了所有在 array1 中但是不在任何其它参数数组中的值，不比较键名
    * @param $array1 $array2, 
    * @return array
    * @warn maybe TypeError Exception
    * @author dongjiang.dongj@
    */
    static function array_diff_recursive($array1, $array2)
    {
        if (gettype($array1) != "array" || gettype($array2) != "array") {
            throw new TypeError("type of import is not array! type:".gettype($array1)." ".gettype($array2), RETTYPE::ERR);
        } else {
            $difference = array();
            foreach($array1 as $value)
            {   
                if(is_array($value))
                {   
                    $arr_count = 0;
                    $tmp_arr = array();

                    foreach(array_values($array2) as $_v) {
                        if(is_array($_v)) {
                            $arr_count ++;
                            array_push($tmp_arr, $_v);
                        }
                    }

                    if($arr_count == 0)
                    {   
                        array_push($difference, $value);
                    }   
                    else
                    {   
                        $_new_diff = array();
                        $_flag = false;
                        foreach($tmp_arr as $_v) {
                            $new_diff = Uarray::array_diff_recursive($value, $_v);
                            if($new_diff != array())
                            {   
                                if ($_new_diff == array()) {
                                    $_new_diff = $new_diff;
                                }
                                continue;
                            } else {
                                $_flag = true;
                                break;
                            }
                        }
                        if ($_flag !== true) {
                            array_push($difference, $_new_diff);
                        }
                    }   
                }   
                else if(!in_array($value, array_values($array2)))
                {   
                    array_push($difference, $value);
                }   
            }   
            return !isset($difference) ? array() : $difference;
        }
    }

    /**
    *  获取多级数组中的内部数组
    * @param array $arr 需要获取内部数组的原始数组
    * @param string $path 数组下表组合，有多个下标时用"."分隔,为空时则返回原始数组. like: key.key1.key2
    * @param string $split 数组下标分隔符， default："."
    * @return mixed $ret 有对应path的数组存在时，返回查询到的数据; 如果对应path的数组不存在，返回 null
    * @warn maybe Input TypeError Exception
    * @author dongjiang.dongj
    */ 

    static public function SubArray($arr, $path="", $split=".")
    {
        if (gettype($arr) != "array" || gettype($path) != "string" || gettype($split) != "string") {
            throw new TypeError("type of import is not right types! type:".gettype($arr)." ".gettype($path)." ".gettype($split), RETTYPE::ERR);
        }

        $ret = $arr;
        if( $path != "")
        {   
            $patharr = explode($split, trim($path, $split));
            $pathcnt = count($patharr);
            for($i=0; $i<$pathcnt; $i++)
            {   
                $key = $patharr[$i];
                if ( isset( $ret[$key] ))
                {   
                    $ret = $ret[$key];
                }   
                else
                {   
                    logging::warn("Can not find ".$key." in ", $ret);
                    return null;
                }   
            }
            return $ret;
        } else  {
            return $arr;
        }
    }

    /**
    *  将string解析为多层次array，通过传入的split array
    * @param string $str 需要获取内部数组的原始数组
    * @param string $split 数组下标分隔符， default：array(). like: array("/001", "/002", ...)
    * @return array  层次array, 无key
    * @warn maybe Input TypeError Exception
    * @author dongjiang.dongj
    */ 
    static public function ParseStringToArray($str, $splits = array()) {
        if (gettype($str) != "string" || gettype($splits) != "array") {
            throw new TypeError("type of import is not right types! type:".gettype($str)." ".gettype($splits), RETTYPE::ERR);
        } else {
            $ret = array();
            $_ret = Uarray::_parse($str, $splits);
            if (gettype($_ret) === "string") {
                $ret[] = $_ret;
            } else {
                $ret = $_ret;
            }
            return $ret;
        }
        
    }
    static private function _parse($str, $splits = array()) {
        if (gettype($str) != "string" || gettype($splits) != "array") {
            throw new TypeError("type of import is not right types! type:".gettype($str)." ".gettype($splits), RETTYPE::ERR);
        } else {
            $ret = array();
            $split = array_shift($splits);
            if ($split !== null) {
                $_tmpArr = explode($split, trim($str));
                foreach($_tmpArr as $v) {
                    $ret[] = Uarray::_parse($v, $splits);
                }
                return $ret;
            } else {
                return $str;
            }
        }
    }


    /** 析构方法 */
    public function __destruct(){
        parent::__destruct();
    }
}

?>
