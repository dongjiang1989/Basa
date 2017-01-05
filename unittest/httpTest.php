<?php
require_once(dirname(__FILE__)."/../src/com/logging.php");
require_once(dirname(__FILE__)."/../src/util/protocol/http.php");
class Testhttp extends PHPUnit_Framework_TestCase {
    private $http;
    public function setup() {
        $this->http = new http();
    }

    public function test_get() {
        $ret = http::get("dongjiang");
        $this->assertEquals(null, $ret);

        $ret = http::get("http://www.baidu.com");
        $this->assertNotEquals(null, $ret);
        
        $ret = http::get("http://fer1a2.bts.kgb.cm3.tbsite.net/?name=tbuad&pid=419095_1006&p4p=p4presult1&catid=&keyword=iphone&count=2&t=1241658587166&o=j&rct=2");
        $this->assertNotEquals(null, $ret);
        
        $ret = http::get("");
        $this->assertEquals(null, $ret);
    }

    public function test_setproxy() {
        $ret = http::setProxy("aaaa");
        $this->assertEquals(True, $ret);

        $ret = http::getProxy();
        $this->assertEquals("aaaa", $ret);

        try {
            $ret = $this->http->aaaa();
            $this->assertTrue(False);
        } catch (CallFunctionFail $e) {
            $this->assertTrue(True);
        }
    }

    function test_post() {
        $ret = http::post("http://10.189.195.55/dms/node/report?node_product=kgb&node=test.bja&role=test1&version_list={\"a\":{\"product\":\"kgb\",\"name\":\"a\",\"bucket\":\"current\",\"version\":\"1\",\"col\":\"\"}, \"b\":{\"product\":\"kgb\",\"name\":\"b\",\"bucket\":\"current\",\"version\":\"1\",\"col\":\"\"}}");
        logging::debug($ret);

        $ret = http::post("http://www.baidu.com");
        logging::debug($ret);
        $this->assertNotEquals("adfa", $ret);
        
        $ret = http::post("dongjiang");
        $this->assertEquals(null, $ret);
        
        $ret = http::post("http://fer1a2.bts.kgb.cm3.tbsite.net/?name=tbuad&pid=419095_1006&p4p=p4presult1&catid=&keyword=iphone&count=2&t=1241658587166&o=j&rct=2");
        logging::debug($ret);
        $this->assertEquals(null, $ret);
        
        $ret = http::post("");
        $this->assertEquals(null, $ret);

        $ret = http::post("http://10.189.195.55/dms/node/report?node_product=kgb&node=test.bja&role=test1&version_list={\"a\":{\"product\":\"kgb\",\"name\":\"a\",\"bucket\":\"current\",\"version\":\"1\",\"col\":\"\"}, \"b\":{\"product\":\"kgb\",\"name\":\"b\",\"bucket\":\"current\",\"version\":\"1\",\"col\":\"\"}}");
        logging::debug($ret);
    }

    function test_setTimeout() {
        $ret = http::setTimeout(2);
        $this->assertEquals(2, http::getTimeout());
        $ret = http::setTimeout("aa");
        $this->assertEquals(1, http::getTimeout());
    }

    public function tearDown() {
        unset($this->http);
    }

}
?>
