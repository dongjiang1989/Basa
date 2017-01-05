<?php
require_once(dirname(__FILE__)."/../src/logmanager.php");
class TestSingleLogPlugin extends PHPUnit_Framework_TestCase {
    function setup() {
        LogManager::RegisterAllPlugins();
        $CM = new LogManager();
        $CM->RegisterAllPlugins();
        $Obj = $CM->getInstanceHandle("Single", array(dirname(__FILE__)."/singlelog/singlelog.log"));
#        var_dump($Obj);
    }

    function test_get() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("Single", array(dirname(__FILE__)."/singlelog/singlelog.log"));
        $Obj->execute("cp -rf ".dirname(__FILE__)."/singlelog/singlelog.log " .dirname(__FILE__)."/singlelog/tmpsinglelog.log");

        $ret = $Obj->get();
        $this->assertFalse($ret);    

        $Obj->execute("cat " .dirname(__FILE__)."/singlelog/newlog.log"." >> ".dirname(__FILE__)."/singlelog/singlelog.log");
        $ret = $Obj->get();
        $res = array("2015-01-21 11:49:20[Info] queryk2?sw=1&url=&rowcount=10&acookie=&elemtid=1&pid=419095_1006","2015-01-21 11:49:20[Info] queryk2?sw=1");
        $this->assertEquals($ret,$res);    

        $ret = $Obj->get("taobaolog");
        $res = array(array("sw"=>"1","url"=>"","rowcount"=>"10","acookie"=>"","elemtid"=>"1","pid"=>"419095_1006"),array("sw"=>"1"));
        $this->assertEquals($ret,$res);    

        $ret = $Obj->get("other");
        $this->assertFalse($ret);

        $ret = $Obj->get("1","2");
        $this->assertFalse($ret);    

        $Obj->execute("mv ".dirname(__FILE__)."/singlelog/tmpsinglelog.log " .dirname(__FILE__)."/singlelog/singlelog.log");
}

    function test_line() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("Single", array(dirname(__FILE__)."/singlelog/singlelog.log"));
        $Obj->execute("cp -rf ".dirname(__FILE__)."/singlelog/singlelog.log " .dirname(__FILE__)."/singlelog/tmpsinglelog.log");
        $ret = $Obj->line();
        $this->assertEquals($ret, 0);

        $Obj->execute("echo 'test' >> " .dirname(__FILE__)."/singlelog/singlelog.log");
        $ret = $Obj->line();
        $this->assertEquals($ret, 1);

        $Obj->execute("echo 'null' > " .dirname(__FILE__)."/singlelog/singlelog.log");
        $ret = $Obj->line();
        $this->assertFalse($ret);    

        $Obj->execute("mv ".dirname(__FILE__)."/singlelog/tmpsinglelog.log " .dirname(__FILE__)."/singlelog/singlelog.log");
    }
    function test_isroll() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("Single", array(dirname(__FILE__)."/singlelog/singlelog.log"));
        $Obj->execute("cp -rf ".dirname(__FILE__)."/singlelog/singlelog.log " .dirname(__FILE__)."/singlelog/tmpsinglelog.log");
        $ret = $Obj->isroll();
        $this->assertFalse($ret);    

        $Obj->execute("echo 'test' >> " .dirname(__FILE__)."/singlelog/singlelog.log");
        $ret = $Obj->isroll();
        $this->assertTrue($ret);

        $Obj->execute("echo 'null' > " .dirname(__FILE__)."/singlelog/singlelog.log");
        $ret = $Obj->isroll();
        $this->assertFalse($ret);    

        $Obj->execute("mv ".dirname(__FILE__)."/singlelog/tmpsinglelog.log " .dirname(__FILE__)."/singlelog/singlelog.log");
    }
    function test_isexist() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("Single", array(dirname(__FILE__)."/singlelog/singlelog.log"));
        $Obj->execute("cp -rf ".dirname(__FILE__)."/singlelog/singlelog.log " .dirname(__FILE__)."/singlelog/tmpsinglelog.log");
        $ret = $Obj->isexist();
        $this->assertFalse($ret);    

        $Obj->execute("echo 'wanlingtest' >> " .dirname(__FILE__)."/singlelog/singlelog.log");
        $ret = $Obj->isexist("wanlingtest");
        $this->assertTrue($ret);    

        $ret = $Obj->isexist("tcmad");
        $this->assertFalse($ret);

        $ret = $Obj->isexist("wanlingtest","tcmad");
        $this->assertTrue($ret);    

        $Obj->execute("mv ".dirname(__FILE__)."/singlelog/tmpsinglelog.log " .dirname(__FILE__)."/singlelog/singlelog.log");
    }
    function test_search() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("Single", array(dirname(__FILE__)."/singlelog/singlelog.log"));
        $Obj->execute("cp -rf ".dirname(__FILE__)."/singlelog/singlelog.log " .dirname(__FILE__)."/singlelog/tmpsinglelog.log");
        $ret = $Obj->search();
        $this->assertFalse($ret);    

        $ret = $Obj->search("tcmad");
        $this->assertFalse($ret);

        $Obj->execute("echo 'wanlingtest' >> " .dirname(__FILE__)."/singlelog/singlelog.log");
        $ret = $Obj->search("wanlingtest");
        $res = array("wanlingtest"=>array("wanlingtest"));
        $this->assertEquals($ret,$res);    

        $ret = $Obj->search("tcmad");
        $this->assertFalse($ret);

        $ret = $Obj->search("wanlingtest","tcmad");
        $this->assertEquals($ret,$res);    

        $Obj->execute("mv ".dirname(__FILE__)."/singlelog/tmpsinglelog.log " .dirname(__FILE__)."/singlelog/singlelog.log");
    }
    function test_seek() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("Single", array(dirname(__FILE__)."/singlelog/singlelog.log"));
        $Obj->execute("cp -rf ".dirname(__FILE__)."/singlelog/singlelog.log " .dirname(__FILE__)."/singlelog/tmpsinglelog.log");
        $ret = $Obj->seek();
        $this->assertFalse($ret);    

        $ret = $Obj->seek("/null/");
        $this->assertFalse($ret);    

        $Obj->execute("echo 'wanlingtest' >> " .dirname(__FILE__)."/singlelog/singlelog.log");
        $Obj->execute("echo 'wanltest' >> " .dirname(__FILE__)."/singlelog/singlelog.log");

        $ret = $Obj->seek("/wan.*est/");
        $res = array("wanlingtest","wanltest");
        var_dump($res);
        $this->assertEquals($ret,$res);   
 
        $ret = $Obj->seek("/t.*ad/");
        $this->assertFalse($ret);

        $ret = $Obj->seek("/wan.*test/","/t.*ad/");
        $this->assertFalse($ret);

        $Obj->execute("mv ".dirname(__FILE__)."/singlelog/tmpsinglelog.log " .dirname(__FILE__)."/singlelog/singlelog.log");
    }


    function test_call() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("Single", array(dirname(__FILE__)."/singlelog/singlelog.log"));
        try {
            $Obj->aaaa();
        } catch (CallFunctionFail $e) {
            $e->getcode();
            $e->__toString();
            $this->assertEquals($e->getcode(), RETTYPE::ERR);
        }
    
    }

}
?>
