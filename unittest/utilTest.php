<?php
require_once(dirname(__FILE__)."/../src/com/util.php");
class TestUtil extends PHPUnit_Framework_TestCase {
    public function test_util_ClassIsExist() {
        try {
            throw new ClassIsExist('class is Exist!!', -1);
            $this->assertFalse(True);
        }
        catch (ClassIsExist $e){
            logging::info("test ok. \n".$e->__tostring());
            $this->assertTrue(true);
        }
    }
    public function test_func_alias(){
        function aaa() {
            return __FUNCTION__;
        }

        func_alias('bbb', 'aaa');
        $ret = bbb();
        $this->assertEquals('aaa', $ret);
    }
    public function test_util_AuthenticateFail() {
        try {
            throw new AuthenticateFail('AuthenticateFail', -1);
            $this->assertFalse(True);
        }
        catch (AuthenticateFail $e)
        {
            logging::debug("test ok! \n".$e);
            $this->assertTrue(true);
        }
    }
    
    public function test_util_TypeError() {
        try {
            throw new TypeError('AuthenticateFail', -1);
            $this->assertFalse(True);
        }
        catch (TypeError $e)
        {
            logging::debug("test ok! \n".$e);
            $this->assertTrue(true);
        }
    }
    
    public function test_util_InputError() {
        try {
            throw new InputError('AuthenticateFail', -1);
            $this->assertFalse(True);
        }
        catch (InputError $e)
        {
            logging::debug("test ok! \n".$e);
            $this->assertTrue(true);
        }
    }

    public function test_util_InputError2() {
        try {
            throw new InputError('AuthenticateFail', -1);
            $this->assertFalse(True);
        }
        catch (Exception $e)
        {
            logging::debug("test ok! \n".$e);
            $this->assertTrue(true);
        }
    }

    public function test_util_is_local() {
        $ret = is_local('123');
        logging::info($ret==False);
        $this->assertEquals(False, $ret);
        $ret = is_local('127.0.0.1');
        logging::info($ret==True);
        $this->assertEquals(True, $ret);

        try {
            $ret = is_local(dddd);
            $this->assertTrue(False);
        }
        catch(Exception $e){
            logging::info(true);
            $this->assertTrue(True);
        }
    }

    public function test_is_local() {
        //function gethostbyname($a) {
        //    throw new Exception("aaaa");
        //}
        
        $ret = is_local($aa=1);
        $this->assertEquals(False, $ret);
    }

    public function test_util_is_same_host() {
        $ret = is_same_host('127.0.0.1', 'aaa');
        logging::info($ret==False);
        $this->assertEquals(False, $ret);

        $ret = is_same_host('127.0.0.1', '0.0.0.0');
        logging::info($ret==True);
        $this->assertEquals(True, $ret);

        $ret = is_same_host('root@127.0.0.1', 'dongjiang.dongj@0.0.0.0');
        logging::info($ret==True);
        $this->assertEquals(True, $ret);
    
        $ret = is_same_host('root@v125051188.bja', 'dongjiang.dongj@v125051188.bja');
        logging::info($ret==True);
        $this->assertEquals(True, $ret);

        $ret = is_same_host('v132098.sqa.cm4', 'dongjiang.dongj@v125051188.bja');
        logging::info($ret==False);
        $this->assertEquals(False, $ret);

        $ret = is_same_host('10.125.51.188', 'v125051188.bja');
        logging::info($ret==True);
        $this->assertEquals(True, $ret);
    }

    public function test_util_build_authenticate() { 
        $ret = build_authenticate('127.0.0.1');
        logging::info($ret==True);
        //$this->assertEquals(True, $ret);
        $ret = is_authenticate('127.0.0.1');
        logging::info($ret==True);
        //$this->assertEquals(True, $ret);

        try {
            $ret = build_authenticate("255.255.255.253");
            $this->assertTrue(False);
        } 
        catch ( AuthenticateFail $e ) {
            $this->assertTrue(True);
        }

        try {
            $ret = build_authenticate("aaaa");
            $this->assertTrue(False);
        } 
        catch ( AuthenticateFail $e ) {
            $this->assertTrue(True);
        }
    }

    public function test_util_ArrayToString() {
        $ret = ArrayToString(array(1,2,3));
        logging::info($ret=="1\n2\n3");
        $this->assertEquals("1\n2\n3", $ret);
    }

    public function test_util_String2Array() {
        $ret = StringToArray("aaaaaaa\n bbbb\n
        ");
        logging::debug($ret);
        $this->assertEquals(array("aaaaaaa\n","bbbb\n
", "", "", "", "", "", "", "", ""), $ret);
    }

    public function test_logging() {
        $ret = logging::info($ret="1\n2\n3");
        $this->assertEquals(True, $ret);
        $ret = logging::error("aaaa");
        $this->assertEquals(True, $ret);
        $ret = logging::warn("aaaa");
        $this->assertEquals(True, $ret);
        $ret = logging::debug("vvvvv");
        $this->assertEquals(True, $ret);
    }

    public function test_RETTYPE() {
        $obj = new RETTYPE();
        $this->assertEquals('-1', $obj::ERR);

        $this->assertEquals('-1', RETTYPE::ERR);
    }

    public function test_updated_data_TS() {
        global $_Ts_php_common_used;
        $_Ts_php_common_used = true;
        $this->assertEquals(true, $_Ts_php_common_used);
        logging::debug($_Ts_php_common_used);    
        updated_data_TS();
        logging::debug($_Ts_php_common_used);    
        $this->assertEquals(false, $_Ts_php_common_used);
        updated_data_TS();
        logging::debug($_Ts_php_common_used);    
        $this->assertEquals(false, $_Ts_php_common_used);
    }

    public function test_safe_base64_encode() {
        $aaa= "dadfrefadfaefadcadfaefr???????URL????????????????";
        $ret = safe_base64_encode($aaa);
        logging::info($ret);
        $ret = safe_base64_decode($ret);
        logging::info($ret);
        $this->assertEquals($aaa, $ret);
        
        $aaa= "dfaes_a__===";
        $ret = safe_base64_encode($aaa);
        logging::info($ret);
        $ret = safe_base64_decode($ret);
        logging::info($ret);
        $this->assertEquals($aaa, $ret);
    }
    
    public function test_ads_base64_encode() {
        $aaa= "dadfrefadfaefadcadfaefr???????URL????????????????";
        $ret = ads_base64_encode($aaa);
        logging::info($ret);
        $ret = ads_base64_decode($ret);
        logging::info($ret);
        $this->assertEquals($aaa, $ret);
        
        $aaa= "dfaes_a__===";
        $ret = ads_base64_encode($aaa);
        logging::info($ret);
        $ret = ads_base64_decode($ret);
        logging::info($ret);
        $this->assertEquals($aaa, $ret);
    }


    

}
?>
