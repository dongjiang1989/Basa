<?php
require_once(dirname(__FILE__)."/../src/com/logging.php");
require_once(dirname(__FILE__)."/../src/util/protobuf/pb.php");
class TestPB extends PHPUnit_Framework_TestCase {
    private $pb;
    public function setup() {
        $this->pb = new pb(dirname(__FILE__)."/person.proto");
    }

    public function test_Serialize() {
        
        $base_array = array("name"=>"aa", "id"=>123, "ok"=>array(1,2,3), "pt"=>array(0,1,2), "phone"=>array(array("aaa"=>"aaaaaa", "bb"=>12111)));

        $string = pb::Serialize($base_array, dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));
        logging::debug($string);
    
        $arr = pb::Deserialize($string, dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));

        logging::debug($arr);

        $string2 = pb::Serialize( $arr, dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));

        logging::debug($string2);

        $arr2 = pb::Deserialize($string2, dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));
        
        logging::debug($arr2);
        logging::debug($base_array);
        $this->assertEquals($base_array, $arr2);
        $this->assertEquals($base_array, $arr);

        $this->assertEquals($string2, $string);
    }

    function test_Serialize1() {
        $base_array = array("name"=>"aa", "id"=>123, "ok"=>array(1,2,3), "pt"=>array(0,1,2), "phone"=>array(array("aaa"=>"aaaaaa", "bb"=>12111), array("aaa"=>1, "bb"=>234)), "phone1" => array(array("aaa"=>"phone1", "bb"=>111111)), "phone2"=> array("aaa"=>"phone2", "bb"=>12312));
        $string = pb::Serialize($base_array, dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));
        logging::debug($string);
        $arr = pb::Deserialize($string, dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));
        logging::debug($arr);
    
        $this->assertEquals($base_array, $arr);
        
        
    }

    function test_Serialize2() {
        $base_array = array("name"=>"中国", "id"=>123, "ok"=>array(1,2,3), "email"=>"dongjiang.dongj@test.com", "pt"=>array(1,1,1,1,1,1,1,1,1,2), "phone"=>array(array("aaa"=>"aaaaaaa"), array("bb"=>1, "aaa"=>"222222")), "phone1" => array(array("aaa"=>"phone1", "bb"=>111111)),"phone2"=> array("aaa"=>"phone2", "bb"=>12312));
        $string = pb::Serialize($base_array, dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));
        $arr = pb::Deserialize($string, dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));        
        logging::debug($arr);
        $this->assertEquals($base_array, $arr);
    }

    public function tearDown() {
        unset($this->pb);
    }

    function test_Serialize_err() {
        $base_array = array("name"=>"中国", "id"=>123, "ok"=>array(1,2,3), "email"=>"dongjiang.dongj@test.com", "pt"=>array(1,1,1,1,1,1,1,1,1,2), "phone"=>array(array("aaa"=>"aaaaaaa"), array("bb"=>1, "aaa"=>"222222")), "phone1" => array(array("aaa"=>"phone1", "bb"=>111111)),"phone2"=> array("aaa"=>"phone2", "bb"=>12312));

        try {
            $string = pb::Serialize($base_array, dirname(__FILE__), "Person", dirname(__FILE__));
            $this->assertTrue(False);
        } catch(ProtofileErr $e) {
            $this->assertTrue(True);
        }
        try {
            $string = pb::Deserialize($base_array, dirname(__FILE__), "Person", dirname(__FILE__));
            $this->assertTrue(False);
        } catch(ProtofileErr $e) {
            $this->assertTrue(True);
        }

        try {
            exec("touch aaa");
            $string = pb::Serialize($base_array, dirname(__FILE__)."/aaa", "Person", dirname(__FILE__));
            $this->assertTrue(False);
        } catch(ProtofileErr $e) {
            $this->assertTrue(True);
            exec("rm -rf aaa");
        }
        try {
            exec("touch aaa");
            $string = pb::Deserialize($base_array, dirname(__FILE__)."/aaa", "Person", dirname(__FILE__));
            $this->assertTrue(False);
        } catch(ProtofileErr $e) {
            $this->assertTrue(True);
            exec("rm -rf aaa");
        }

        try {
            $string = pb::Serialize($base_array, dirname(__FILE__)."/person.proto", "AAAAAAAAAAAAAAA", dirname(__FILE__));
            $this->assertTrue(False);
        } catch(ClassNotExist $e) {
            $this->assertTrue(True);
        }
        try {
            $string = pb::Deserialize($base_array, dirname(__FILE__)."/person.proto", "AAAAAAAAAAAAAAA", dirname(__FILE__));
            $this->assertTrue(False);
        } catch(ClassNotExist $e) {
            $this->assertTrue(True);
        }

    }

    function test_SerializeFromJson() {
        $json = '{"name":"aaa","id":1,"email":"dongjiang.dongj@test.com","ok":["1","2","3","4","5","6","7"],"pt":[1,1,1,1,2],"phone":[{"aaa":"aaaaaa","bb":0}]}';
        $string = pb::SerializeFromJson($json, dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));
        $myjson = pb::DeserializeToJson($string, dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));

        $this->assertEquals($json, $myjson);

        try {
            $myjson = pb::DeserializeToJson(substr($string, 1, 1), dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));
            $this->assertTrue(False);
        } catch(ProtofileErr $e) {
            $this->assertTrue(True);
        }
    }

    function test_SerializeFromJson_err() {
        $json = 'aaaaaaaaa';
        try {
            $string = pb::SerializeFromJson($json, dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));
            $this->assertTrue(False);
        } catch(TypeError $e) {
            $this->assertTrue(True);
        }
        
    }

}
?>
