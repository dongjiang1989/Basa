<?php
require_once(dirname(__FILE__)."/../src/logmanager.php");
class TestPVlogPlugin extends PHPUnit_Framework_TestCase {
    function setup() {
        LogManager::RegisterAllPlugins();
        $CM = new LogManager();
        $CM->RegisterAllPlugins();
        $Obj = $CM->getInstanceHandle("PVLog", array(dirname(__FILE__)."/pvlog/pvlog.log"));
#        var_dump($Obj);
    }

    function test_line() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("PVLog", array(dirname(__FILE__)."/pvlog/pvlog.log"));
        $Obj->execute("cp -rf ".dirname(__FILE__)."/pvlog/pvlog.log " .dirname(__FILE__)."/pvlog/tmppvlog.log");
        $ret = $Obj->line();
        $this->assertEquals($ret, 0);
        $Obj->execute("echo 'test' >> " .dirname(__FILE__)."/pvlog/pvlog.log");
        $ret = $Obj->line();
        $this->assertEquals($ret, 1);
        $Obj->execute("echo 'null' > " .dirname(__FILE__)."/pvlog/pvlog.log");
        $ret = $Obj->line();
        $this->assertFalse($ret);    
        $Obj->execute("mv ".dirname(__FILE__)."/pvlog/tmppvlog.log " .dirname(__FILE__)."/pvlog/pvlog.log");
    }
    function test_isroll() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("PVLog", array(dirname(__FILE__)."/pvlog/pvlog.log"));
        $Obj->execute("cp -rf ".dirname(__FILE__)."/pvlog/pvlog.log " .dirname(__FILE__)."/pvlog/tmppvlog.log");
        $ret = $Obj->isroll();
        $this->assertFalse($ret);    
        $Obj->execute("echo 'test' >> " .dirname(__FILE__)."/pvlog/pvlog.log");
        $ret = $Obj->isroll();
        $this->assertTrue($ret);
        $Obj->execute("echo 'null' > " .dirname(__FILE__)."/pvlog/pvlog.log");
        $ret = $Obj->isroll();
        $this->assertFalse($ret);    
        $Obj->execute("mv ".dirname(__FILE__)."/pvlog/tmppvlog.log " .dirname(__FILE__)."/pvlog/pvlog.log");
    }
    function test_isexist() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("PVLog", array(dirname(__FILE__)."/pvlog/pvlog.log"));
        $Obj->execute("cp -rf ".dirname(__FILE__)."/pvlog/pvlog.log " .dirname(__FILE__)."/pvlog/tmppvlog.log");
        $ret = $Obj->isexist();
        $this->assertFalse($ret);    
        $Obj->execute("echo 'wanlingtest' >> " .dirname(__FILE__)."/pvlog/pvlog.log");
        $ret = $Obj->isexist("wanlingtest");
        $this->assertTrue($ret);    
        $ret = $Obj->isexist("tcmad");
        $this->assertFalse($ret);
        $ret = $Obj->isexist("wanlingtest","tcmad");
        $this->assertTrue($ret);    
        $Obj->execute("mv ".dirname(__FILE__)."/pvlog/tmppvlog.log " .dirname(__FILE__)."/pvlog/pvlog.log");
    }
    function test_search() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("PVLog", array(dirname(__FILE__)."/pvlog/pvlog.log"));
        $Obj->execute("cp -rf ".dirname(__FILE__)."/pvlog/pvlog.log " .dirname(__FILE__)."/pvlog/tmppvlog.log");
        $ret = $Obj->search();
        $this->assertFalse($ret);    

        $ret = $Obj->search("tcmad");
        $this->assertFalse($ret);

        $Obj->execute("echo 'wanlingtest' >> " .dirname(__FILE__)."/pvlog/pvlog.log");
        $ret = $Obj->search("wanlingtest");
        $res = array("wanlingtest"=>array(array("wanlingtest")));
        $this->assertEquals($ret,$res);    

        $ret = $Obj->search("tcmad");
        $this->assertFalse($ret);

        $ret = $Obj->search("wanlingtest","tcmad");
        $this->assertEquals($ret,$res);    

        $Obj->execute("mv ".dirname(__FILE__)."/pvlog/tmppvlog.log " .dirname(__FILE__)."/pvlog/pvlog.log");
    }
    function test_seek() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("PVLog", array(dirname(__FILE__)."/pvlog/pvlog.log"));
        $Obj->execute("cp -rf ".dirname(__FILE__)."/pvlog/pvlog.log " .dirname(__FILE__)."/pvlog/tmppvlog.log");
        $ret = $Obj->seek();
        $this->assertFalse($ret);    

        $ret = $Obj->seek("/null/");
        $this->assertFalse($ret);    

        $Obj->execute("echo 'wanlingtest' >> " .dirname(__FILE__)."/pvlog/pvlog.log");

        $ret = $Obj->seek("/wan.*est/");
        $res = array(array("wanlingtest"));
        var_dump($ret);
        var_dump($res);
        $this->assertEquals($ret,$res);   
 
        $ret = $Obj->seek("/t.*ad/");
        $this->assertFalse($ret);

        $ret = $Obj->seek("/wan.*test/","/t.*ad/");
        $this->assertFalse($ret);

        $Obj->execute("mv ".dirname(__FILE__)."/pvlog/tmppvlog.log " .dirname(__FILE__)."/pvlog/pvlog.log");
    }
    function test_get() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("PVLog", array(dirname(__FILE__)."/pvlog/pvlog.log"));
        $Obj->execute("cp -rf ".dirname(__FILE__)."/pvlog/pvlog.log " .dirname(__FILE__)."/pvlog/tmppvlog.log");

        $ret = $Obj->get();
        $this->assertFalse($ret);    

        $Obj->execute("cat " .dirname(__FILE__)."/pvlog/newpv.log"." >> ".dirname(__FILE__)."/pvlog/pvlog.log");
        $ret = $Obj->get();
        $res = array(array("1.8",array("1418286934","419095_1006","v189199160","10.189.199.161","","81f0","","","","","0,0,4;;,","","","","","curl/75","","","","1",""),"",array("1","","","","","","","","1"),"",array("1","1","/tcmad?pid=419095_1006&ip=161&count=10",array(array("2000","algoqr3"),array("0","1","2","0","0","16","8000","C:16:502")),""),array(array("21001_11001_41001_9","cc-goodsid","31001","11777","N","D","11","100","1","2","5","51001","1","T","p","21","100000001,,24","1","","","0")),"","","","","81fe"),array("1.9",array("1418286934","419095_1006","v189199160","10.189.199.161","","81f0","","","","","0,0,4;;,","","","","","curl/75","","","","1",""),"",array("1","","","","","","","","1"),"",array("1","1","/tcmad?pid=419095_1006&ip=161&count=10",array(array("2000","algoqr3"),array("0","1","2","0","0","16","8000","C:16:502")),""),array(array("21001_11001_41001_9","cc-goodsid","31001","11777","N","D","11","100","1","2","5","51001","1","T","p","21","100000001,,24","1","","","0")),"","","","","81fe"));
        var_dump($ret);
        var_dump($res);
        $this->assertEquals($ret,$res);    

        $ret = $Obj->get("qr");
        $res = array(array(array("2000","algoqr3"),array("0","1","2","0","0","16","8000","C:16:502")),array(array("2000","algoqr3"),array("0","1","2","0","0","16","8000","C:16:502")));
        var_dump($ret);
        var_dump($res);
        $this->assertEquals($ret,$res);    

        $ret = $Obj->get("query");
        $res = array(array("pid"=>"419095_1006","ip"=>"161","count"=>"10"),array("pid"=>"419095_1006","ip"=>"161","count"=>"10"));
        var_dump($ret);
        var_dump($res);
        $this->assertEquals($ret,$res);    

        $ret = $Obj->get("ads");
        $res = array(array(array("21001_11001_41001_9","cc-goodsid","31001","11777","N","D","11","100","1","2","5","51001","1","T","p","21","100000001,,24","1","","","0")),array(array("21001_11001_41001_9","cc-goodsid","31001","11777","N","D","11","100","1","2","5","51001","1","T","p","21","100000001,,24","1","","","0")));
        var_dump($ret);
        var_dump($res);
        $this->assertEquals($ret,$res);    

        $ret = $Obj->get("other");
        $this->assertFalse($ret);

        $ret = $Obj->get("value","0");
        $res = array("1.8","1.9");
        var_dump($ret);
        var_dump($res);
        $this->assertEquals($ret,$res);    

        $ret = $Obj->get("value","1","2");
        $res = array("v189199160","v189199160");
        var_dump($ret);
        var_dump($res);
        $this->assertEquals($ret,$res);    

        $ret = $Obj->get("value","6","0","2");
        $res = array("31001","31001");
        var_dump($ret);
        var_dump($res);
        $this->assertEquals($ret,$res);    

        $ret = $Obj->get("value","5","4");
        $res = array("","");
        var_dump($ret);
        var_dump($res);
        $this->assertEquals($ret,$res);    

        $ret = $Obj->get("value","5","3","0","1");
        $res = array("algoqr3","algoqr3");
        var_dump($ret);
        var_dump($res);
        $this->assertEquals($ret,$res);    

        $ret = $Obj->get("value","5","3","0");
        $res = array(array("2000","algoqr3"),array("2000","algoqr3"));
        var_dump($ret);
        var_dump($res);
        $this->assertEquals($ret,$res);    

        $ret = $Obj->get("value","1","2","3","4","5");
        $this->assertFalse($ret);    

        $ret = $Obj->get("other","1","2","3","4","5");
        $this->assertFalse($ret);    

        $Obj->execute("mv ".dirname(__FILE__)."/pvlog/tmppvlog.log " .dirname(__FILE__)."/pvlog/pvlog.log");
    }

    function test_setParseSep() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("PVLog", array(dirname(__FILE__)."/pvlog/pvlog.log"));

        $ret = $Obj->setParseSep("");
        $this->assertTrue($ret);
        $ret = $Obj->getParseSep();
        $this->assertEquals($ret, "\001,\002,\003,\004,\005,\006,\007,\008,\009,\010,\011,\012,\013,\014,\015,\016,\017,\018,\019,\020,\021,\022,\023,\024,\025,\026");

        $ret = $Obj->setParseSep(array());
        $this->assertFalse($ret);
        $ret = $Obj->getParseSep();
        $this->assertEquals($ret, "\001,\002,\003,\004,\005,\006,\007,\008,\009,\010,\011,\012,\013,\014,\015,\016,\017,\018,\019,\020,\021,\022,\023,\024,\025,\026");
        
        $ret = $Obj->setParseSep("aa,bb");
        $this->assertTrue($ret);
        $ret = $Obj->getParseSep();
        $this->assertEquals($ret, "aa,bb");
    }

    function test_getParseSep() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("PVLog", array(dirname(__FILE__)."/pvlog/pvlog.log"));
        $ret = $Obj->getParseSep();
        $this->assertEquals($ret, "\001,\002,\003,\004,\005,\006,\007,\008,\009,\010,\011,\012,\013,\014,\015,\016,\017,\018,\019,\020,\021,\022,\023,\024,\025,\026");
    }

    function test_call() {
        $CM = new LogManager();
        $Obj = $CM->getInstanceHandle("PVLog", array(dirname(__FILE__)."/pvlog/pvlog.log"));
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
