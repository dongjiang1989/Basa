<?php
require_once(dirname(__FILE__)."/../src/util/json/Json.php");
require_once(dirname(__FILE__)."/../src/com/logging.php");

class TestJson extends PHPUnit_Framework_TestCase  {

    public function test_Serialize(){
        $obj = new Json();
        
        $arr1 = array(
            "key1" => "value1",
            "测试key123" => "测试value test",
            "测试@#$" => "value",
        );        
        $this->assertEquals('{"key1":"value1","测试key123":"测试value test","测试@#$":"value"}', $obj->Serialize($arr1));
        
        $arr2 = array(
            "key1" => array("key11"=>1, "11", "键值12"=>"value值"),
            "测试@#$" => "value",
        );        
        $this->assertEquals('{"key1":{"key11":1,"0":"11","键值12":"value值"},"测试@#$":"value"}', $obj->Serialize($arr2));
        $this->assertEquals('{"key1":{"key11":1,"0":"11","键值12":"value值"},"测试@#$":"value"}', Json::Serialize($arr2));
        
        $this->assertEquals('[]', Json::Serialize(array()));
        $this->assertEquals('["a","中国","adfadf中国DA￥3"]', Json::Serialize(array("a",   "中国", "adfadf中国DA￥3")));

        try {
            Json::Serialize("");
            $this->assertTrue(false);
        } catch (InputError $e){
            $this->assertTrue(True);
        }
 
        $stringjson = '{"key1":"value1","测试key123":"测试value test","测试@#$":"value"}';

        $stringjsonGBK = iconv("UTF-8", "GBK", $stringjson);

        Json::isReturnGBk(False);
        $arr = Json::Deserialize($stringjsonGBK);
        $json = Json::Serialize($arr);

        $this->assertEquals($stringjson, $json);
        
        
        Json::isReturnGBk();
        
        $arr = Json::Deserialize($stringjsonGBK);
        $json = Json::Serialize($arr);
        $this->assertEquals($stringjsonGBK, $json);

        Json::isReturnGBk(False);

    }

    public function test_Deserialize(){
        $obj = new Json();
        
        $jsonstr1 = '{"key1":{"key11":     "1",  "键值12":"value值" }, "测试@#$":"value"}';
        $exptstr1 = array(
            "key1" => array("key11"=>1,"键值12"=>"value值"),
            "测试@#$" => "value",
        );        
        $this->assertEquals($exptstr1, $obj->Deserialize($jsonstr1));
        $this->assertEquals($exptstr1, JSon::Deserialize($jsonstr1));
        $this->assertEquals($exptstr1, JSon::Deserialize($jsonstr1, true));

        $str = Json::Serialize($exptstr1);
        $arr = Json::Deserialize($str);
        logging::debug("expt str:",$exptstr1);
        logging::debug("arr is:",$arr);
        $this->assertEquals($exptstr1,$arr);

        Json::isReturnGBk();
        $str = Json::Serialize($exptstr1);
        logging::error("dongjiang", $exptstr1, $str);
        $arr = Json::Deserialize($str);
        logging::error("dongjiang1:", $arr);

        $reflection_class = new ReflectionClass("Json"); 
        $method = $reflection_class->getMethod("_change2GBK");
        $method->setAccessible(true);

        $ajson = new Json();

        $this->assertEquals($method->invoke($ajson, $exptstr1), $arr);    

        Json::isReturnGBk(false);

        try {
            Json::Deserialize(array());
            $this->assertTrue(false);
        } catch (InputError $e){
            $this->assertTrue(True);
        }
        
        try {
            Json::Deserialize("");
            $this->assertTrue(false);
        } catch (TypeError $e){
            $this->assertTrue(True);
        }
            
    }

    public function test_hasKey(){
        $obj = new Json();
        
        $jsonstr1 = '{"key1":{"key11":"1","键值12":"value值"},"测试@#$":"value"}';
        $jsonstr2 = null;
        
        $this->assertEquals(true,$obj->hasKey($jsonstr1,"key1"));
        $this->assertEquals(false,$obj->hasKey($jsonstr1,"key11"));
        $this->assertEquals(false,$obj->hasKey($jsonstr1,"键值12"));
        $this->assertEquals(NULL,$obj->hasKey($jsonstr2,"键值12"));
    
        $jsonstr3 = '{"key1":"wront json format","key2"-"value2"}';
        $this->assertEquals(NULL,$obj->hasKey($jsonstr3,"key1"));
    }
    
    public function test_getValue(){
        $obj = new Json();
        
        $jsonstr1 = '{"key1":{"key11":"1","键值12":"value值"},"测试@#$":"value"}';
        $jsonstr2 = null;
        
        $this->assertEquals(array("key11"=>"1", "键值12"=>"value值"),$obj->getValue($jsonstr1,"key1"));
        // TODO
        $this->assertEquals('value',$obj->getValue($jsonstr1,"测试@#$"));
        $this->assertEquals(false,$obj->getValue($jsonstr1,"key11"));
        $this->assertEquals(false,$obj->getValue($jsonstr1,"键值12"));
        $this->assertEquals(NULL,$obj->getValue($jsonstr2,"key1"));
    
        $jsonstr3 = '{"key1":"wront json format","key2"-"value2"}';
        $this->assertEquals(NULL, Json::getValue($jsonstr3,"key1"));
    }

    public function test_call() {
        $obj = new Json();
        try {
            $obj->aaaaa();
            $this->assertTrue(False);
        } catch (CallFunctionFail $e) {
            $this->assertTrue(True);
        }

        $ret = $obj->aa;
        $this->assertEquals(NULL, $ret);

        $obj->aa = 1;
        $ret = $obj->aa;
        $this->assertEquals(1, $ret);
    }

    public function test_getValueType() {
        $obj = new Json();
        $json = '{"key1":"wront json format","key2":"value2"}';

        $this->assertEquals(Json::getValueType($json, "key1"), $obj->getValueType($json, "key1"));
        $this->assertEquals("string", $obj->getValueType($json, "key1"));
        $this->assertEquals(NULL, $obj->getValueType($json, "aaa"));
        $this->assertEquals(NULL, $obj->getValueType(array(), "aaa"));
        $this->assertEquals(NULL, $obj->getValueType('{"key2"-"value2"}', "aaa"));
    }

    public function test_get() {
        $obj = new Json();
        $json = '{"key1":"wront json format","key2":"value2"}';
        $this->assertEquals(Json::get($json, "key1"), $obj->get($json, "key1"));
        $this->assertEquals(array("wront json format", "string"), $obj->get($json, "key1"));
        $this->assertEquals(NULL, $obj->get($json, "aaa"));
        $this->assertEquals(NULL, $obj->get(array(), "aaa"));
    }
}
?>
