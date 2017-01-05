<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : asserts.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-11-30 16:37
*   Description : 
======================================================*/
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/com/util.php");
require_once(dirname(__FILE__)."/uarray.php");

/**
* 断言失败异常
* @author dongjiang.dongj
*/
class AssertError extends Exception {
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }   

    public function __toString() {
        logging::error(__CLASS__ . ": [ret:{$this->code}]: {$this->message}");
        return __CLASS__ . ": [ret:{$this->code}]: {$this->message}\n";
    }   
}

if (!class_exists('asserts')) {
    /**
    * asserts 测试基准方法，各种断言
    * 使用方法包括：
    *       各种业务使用到的断言，已经各种是否抛异常或返回值模式
    *       异常包括：
    *           assertEqual(mixed $expected, mixed $actual, string $message = "")
    *           assertNotEqual(mixed $expected, mixed $actual, string $message = "")
    *           assertGreater(mixed $expected, mixed $actual, string $message = "")
    *           assertGreaterEqual(mixed $expected, mixed $actual, string $message = "")
    *           assertLess(mixed $expected, mixed $actual, string $message = "")
    *           assertLessEqual(mixed $expected, mixed $actual, string $message = "")
    *           assertInArray(mixed $expected, mixed $actualarr, string $message = "")
    *           assertKeyInArray(mixed $expected, mixed $actualarr, string $message = "")
    *           assertNotInArray(mixed $expected, mixed $actualarr, string $message = "")
    *           assertKeyNotInArray(mixed $expected, mixed $actualarr, string $message = "")
    *           assertAscending(mixed $actualarr, string $message = "")
    *           assertKeyAscending(mixed $actualarr, string $message = "")
    *           assertDescending(mixed $actualarr, string $message = "")
    *           assertKeyDescending(mixed $actualarr, string $message = "")
    * @author dongjiang.dongj
    */
    class asserts extends Object {
        function __construct() {
            parent::__construct();
            updated_data_TS();
            //TODO
        }
        /**
        * mode 规定是否是异常抛出；还是error日志. 默认为异常抛出. 
        */
        const ThrowExcept = 0;
        const PrintError = 1;
        private static $mode = asserts::ThrowExcept; 

        private static function _getType($v1, $v2) {
            if ( (is_numeric($v1) || is_string($v1)) && (is_numeric($v2) || is_string($v2))) {
                return True;
            }
            return False;
        }

        /**
        * 判断是否$expected === $actual
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertEqual( $expected, $actual, $message = "") {
            if ( self::_getType($expected, $actual) ) {
                $expecteded = (string)$expected;
                $actualed = (string)$actual;
            } else {
                $expecteded = $expected;
                $actualed = $actual;
            }
            if ($expecteded === $actualed) {
                return True;
            }
            else {
                $_msg = "expected: \n".print_r($expecteded, true)."\n is not Equal \nactual:\n".print_r($actualed, true)."\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }

        /**
        * 判断是否array values是否相等
        * @return bool 
        * @author dongjiang.dongj@
        * @warn AssertError, InputError
        */
        public static function assertEqualArray( $expected, $actual, $message = "") {
            if (!is_array($actual)) {
                $_msg = print_r($actual, true)." is not array!\n";
                throw new InputError($_msg, RETTYPE::ERR);
            }

            if (!is_array ($expected)) {
                $_msg = print_r($expected, true)." is not array!\n";
                throw new InputError($_msg, RETTYPE::ERR);
            }

            $_ret_diff1 = Uarray::array_diff_recursive($actual, $expected);
            $_ret_diff2 = Uarray::array_diff_recursive($expected, $actual);

            if ($_ret_diff1 != array() || $_ret_diff2 != array()) {
                $_msg = "Array not in Equal! diff:".print_r($_ret_diff1, true)." ".print_r($_ret_diff2, true);
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            } else {
                return True;
            }
        }

        /**
        * 判断是否array keys-values是否相等
        * @return bool 
        * @author dongjiang.dongj@
        * @warn AssertError, InputError
        */
        public static function assertEqualArrayAssoc( $expected, $actual, $message = "") {
            if (!is_array($actual)) {
                $_msg = print_r($actual, true)." is not array!\n";
                throw new InputError($_msg, RETTYPE::ERR);
            }

            if (!is_array($expected)) {
                $_msg = print_r($expected, true)." is not array!\n";
                throw new InputError($_msg, RETTYPE::ERR);
            }

            $_ret_diff1 = Uarray::array_diff_assoc_recursive($actual, $expected);
            $_ret_diff2 = Uarray::array_diff_assoc_recursive($expected, $actual);

            if ($_ret_diff1 != array() || $_ret_diff2 != array()) {
                $_msg = "Array not in Equal! diff:".print_r($_ret_diff1, true)." ".print_r($_ret_diff2, true);
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            } else {
                return True;
            }
        }

        /**
        * 判断是否$expected != $actual
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertNotEqual( $expected, $actual, $message = "") {
            if ($expected !== $actual) {
                return True;
            }
            else {
                $_msg = "expected: \n".print_r($expected, true)."\n is Equal \nactual:\n".print_r($actual, true)."\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }
            
        /**
        * 判断是否$expected < $actual
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertGreater( $expected, $actual, $message = "") {
            if ($expected < $actual) {
                return True;
            }
            else {
                $_msg = "actual: \n".print_r($actual, true)."\n is not greater than \nexpect:\n".print_r($expected, true)."\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
                
            }
        }
        
        /**
        * 判断是否$expected <= $actual
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertGreaterEqual( $expected, $actual, $message = "") {
            if ($expected <= $actual) {
                return True;
            }
            else {
                $_msg = "actual: \n".print_r($actual, true)."\n is not greater equal than \nexpect:\n".print_r($expected, true)."\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }

        /**
        * 判断是否$expected > $actual
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertLess( $expected, $actual, $message = "" ) {
            if ( $expected > $actual ) {
                return True;
            }
            else {
                $_msg = "actual: \n".print_r($actual, true)."\n is not less than \nexpect:\n".print_r($expected, true)."\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }

        /**
        * 判断是否$expected >= $actual
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertLessEqual( $expected, $actual, $message = "" ) {
            if ($expected >= $actual) {
                return True;
            }
            else {
                $_msg = "actual: \n".print_r($actual, true)."\n is not less equal than \nexpect:\n".print_r($expected, true)."\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }

        /**
        * 判断是否 $expected is in array: $actual
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertInArray( $expected, $actualarr, $message = "") {
            if(!is_array($actualarr)){
                $_msg = print_r($actualarr, true)." is not an array\n";
                throw new InputError($_msg, RETTYPE::ERR);
            }

            if (in_array($expected, array_values($actualarr))) {
                return True;
            }
            else {
                $_msg = print_r($expected,true)." is not in array: \n". print_r($actualarr,true)."\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }
        
        /**
        * 判断是否$expected 在array $actual的key 中
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertKeyInArray( $expected, $actualarr, $message = "" ) {
            if( !is_array( $actualarr ) ){
                $_msg = print_r( $actualarr, true )." is not an array\n";  
                throw new InputError($_msg, RETTYPE::ERR);
            }

            if ( array_key_exists( $expected, $actualarr ) ) {
                return True;
            }
            else {
                $_msg ="The expected ".print_r($expected,true)." key is not exist in array :\n ". print_r($actualarr,true)."\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }
        
        /**
        * 判断是否$expected 不在array $actual 中
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertNotInArray( $expected, $actualarr, $message = "") {
            if( !is_array( $actualarr ) ){
                $_msg = print_r($actualarr, true)." is not an array\n";  
                throw new InputError($_msg, RETTYPE::ERR);
            }

            if ( !in_array($expected, $actualarr) ) {
                return True;
            }
            else {
                $_msg = "The expected ".print_r($expected,true)." key is in array :\n". print_r($actualarr,true)."\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }
        
        /**
        * 判断是否$expected 不在array $actual的key 中
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertKeyNotInArray( $expected, $actualarr, $message = "") {
            if(!is_array($actualarr)){
                $_msg = print_r($actualarr, true)." is not an array\n";  
                throw new InputError($_msg, RETTYPE::ERR);
            }

            if (!array_key_exists( $expected, $actualarr )) {
                return True;
            }
            else {
                $_msg ="The expected ".print_r($expected,true)."  exist in array :\n". print_r($actualarr,true)."\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }
        
        /**
        * 判断是否$actualarr 升序
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertAscending( $actualarr, $message = "") {
            if(!is_array($actualarr)){
                $_msg = print_r($actualarr, true)." is not an array\n";  
                throw new InputError($_msg, RETTYPE::ERR);
            }

            if ( empty($actualarr)) {
                logging::warn(print_r($actualarr, true)." is empty\n");
                return True;
            }
            
            $_tmp = $actualarr;
            $_tmpvalue = array_values($_tmp);
            
            $_ret = array_multisort($_tmpvalue, SORT_ASC, SORT_REGULAR);

            $_tmpvalue2 = array_values($_tmp);
            if ($_ret == True && $_tmpvalue2 === $_tmpvalue) {
                logging::debug("The Array Values is :\n".print_r(array_values($actualarr), true));
                return True;
            }
            else {
                $_msg ="The ".print_r(array_values($_tmp), true)." is not Ascending\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }

        /**
        * 判断是否$actualarr 的key 升序
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertKeyAscending( $actualarr, $message = "") {
            if(!is_array($actualarr)){
                $_msg = print_r($actualarr, true)." is not an array\n";  
                throw new InputError($_msg, RETTYPE::ERR);
            }

            if ( empty($actualarr)) {
                logging::warn(print_r($actualarr, true)." is empty\n");
                return True;
            }
            
            $_tmp = $actualarr;
            $_tmpkey = array_keys($_tmp);
            
            $_ret = array_multisort($_tmpkey, SORT_ASC, SORT_REGULAR);

            $_tmpkey2 = array_keys($actualarr);
            if ($_ret == True && $_tmpkey2 === $_tmpkey) {
                logging::debug("The Array Keys is :\n".print_r(array_keys($actualarr), true));
                return True;
            }
            else {
                $_msg ="The ".print_r(array_keys($_tmp), true)." is not Ascending\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }

        /**
        * 判断是否$actualarr 升序
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertDescending( $actualarr, $message = "") {
            if(!is_array($actualarr)){
                $_msg = print_r($actualarr, true)." is not an array\n";  
                throw new InputError($_msg, RETTYPE::ERR);
            }

            if ( empty($actualarr)) {
                logging::warn(print_r($actualarr, true)." is empty\n");
                return True;
            }
            
            $_tmp = $actualarr;
            $_tmpvalue = array_values($_tmp);
            
            $_ret = array_multisort($_tmpvalue, SORT_DESC, SORT_REGULAR);

            $_tmpvalue2 = array_values($_tmp);
            if ($_ret == True && $_tmpvalue2 === $_tmpvalue) {
                logging::debug("The Array Values is :\n".print_r(array_values($actualarr), true));
                return True;
            }
            else {
                $_msg ="The ".print_r(array_values($_tmp), true)." is not Decending\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }

        /**
        * 判断是否$actualarr的key 升序
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError
        */
        public static function assertKeyDescending( $actualarr, $message = "") {
            if(!is_array($actualarr)){
                $_msg = print_r($actualarr, true)." is not an array\n";  
                throw new InputError($_msg, RETTYPE::ERR);
            }

            if ( empty($actualarr)) {
                logging::warn(print_r($actualarr, true)." is empty\n");
                return True;
            }
            
            $_tmp = $actualarr;
            $_tmpkey = array_keys($_tmp);
            
            $_ret = array_multisort($_tmpkey, SORT_DESC, SORT_REGULAR);

            $_tmpkey2 = array_keys($_tmp);
            if ($_ret == True && $_tmpkey2 === $_tmpkey) {
                logging::debug("The Array Keys is :\n".print_r(array_keys($actualarr), true));
                return True;
            }
            else {
                $_msg ="The ".print_r(array_keys($actualarr), true)." is not Decending\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }

        /**
        * 判断 $string 是否match $pattern
            assertRegExp(string $pattern, string $string[, string $message = ''])
        * @return bool
        * @author dongjiang.dongj
        * @warn AssertError，InputError
        */
        public static function assertRegExp($pattern, $string, $message = "") {
            if (!is_string($pattern)) {
                $_msg = print_r($pattern, true)." is not string!\n";
                throw new InputError($_msg, RETTYPE::ERR);
            }

            if (!is_string($string)) {
                $_msg = print_r($string, true)." is not string!\n";
                throw new InputError($_msg, RETTYPE::ERR);
            }

            $_ret = preg_match_all($pattern, $string, $matches);
            if ($_ret == True) {
                return True;
            } else {
                $_msg ="The ".$string." sting is not matches PCRE pattern \"".$pattern."\"\n";
                if ($message != "") {
                    $_msg = $message;
                }
                if ( self::$mode == asserts::ThrowExcept ) {
                    throw new AssertError($_msg, RETTYPE::ERR);
                } else {
                    logging::error($_msg);
                    return False;
                }
            }
        }
        /**
        * 设置 mode 模式： asserts::ThrowExcept or asserts::PrintError .
             if not in asserts::ThrowExcept or asserts::PrintError, be set asserts::ThrowExcept. 
        * @return void
        * @author dongjiang.dongj
        */
        public static function setMode( $mode ) {
            if ($mode == asserts::ThrowExcept) {
                self::$mode = asserts::ThrowExcept;
            } else if ($mode == asserts::PrintError) {
                self::$mode = asserts::PrintError;
            } else {
                logging::warn($mode." is not asserts::ThrowExcept and asserts::PrintError, so set to asserts::ThrowExcept : ".asserts::ThrowExcept);
                self::$mode = asserts::ThrowExcept;
            }
        }
        
        /**
        * 析构方法
        * @author dongjiang.dongj
        */
        public function __destruct(){
            //TODO
            parent::__destruct();
        }
    }
}
else {
    throw new exception("asserts has been definded!");
}
?>
