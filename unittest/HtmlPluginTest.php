<?php
/**
 * User: qigang
 * Date: 14-12-11
 * Time: ÏÂÎç7:48
 * To Test HtmlPlugin
 */
require_once(dirname(__FILE__)."/../src/confmanager.php");
class TestHtmlPlugin extends PHPUnit_Framework_TestCase {

    private $html = null;
//    private $filename = "http://k2fer1a1.mob.et2.tbsite.net/?name=itemjump&pid=419095_1006&p4p=p4presult1&count=10&o=d&ac=chenchenac1&nickname=chenchennick1&elemtid=1&ip=10.232.131.80";
    private $filename = "<html><body><div id='pvlog'>pvloggggggggggggggggggggggg</div></body>";
    function setup() {
        $CM = new ConfigManager();
        $this->html = $CM->getInstanceHandle("html",array($this->filename));
    }
    public function test_getPvlogObj(){
        $pvobj = $this->html->getPvlogObj();
        $this->assertTrue(true,is_object($pvobj));
    }
}