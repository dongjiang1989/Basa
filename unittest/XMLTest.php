<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../src/com/logging.php");
require_once(dirname(__FILE__)."/../src/util/xml/xml.php");
class TestXML extends PHPUnit_Framework_TestCase {
    private $xml;
    public function setup() {
        $this->xml = new xml();
    }

    public function test_xml2array() {
        $string = '<?xml version="1.0" encoding="GBK"?><QUERYRESULT><VERSION>2.1</VERSION><PTOTAL>600</PTOTAL><PTHIS>15</PTHIS><PPUSHLEFT>0</PPUSHLEFT><PRESULTS><PRESULT><ADGROUPID><![CDATA[192174918]]></ADGROUPID><BIDWORDID><![CDATA[53037787149]]></BIDWORDID><CAMPAIGNID><![CDATA[3247188]]></CAMPAIGNID><CREATIVEID><![CDATA[205322419]]></CREATIVEID><CUSTOMERID><![CDATA[1103034176]]></CUSTOMERID><DESC><![CDATA[]]></DESC><CLICKURL><![CDATA[http://item.taobao.com/item.htm?id=17921333262]]></CLICKURL><ST_KEYWORDS><![CDATA[男装短袖]]></ST_KEYWORDS><REDKEY><![CDATA[男装短袖]]></REDKEY><TITLE><![CDATA[3皇冠信誉!2014潮男士短袖T恤 3件55包邮!]]></TITLE><REDKEYS><![CDATA[琛 f 湇 ]]></REDKEYS><RESOURCEID><![CDATA[17921333262]]></RESOURCEID><GOODSPRICE><![CDATA[9800]]></GOODSPRICE><CATID><![CDATA[30,50000436]]></CATID><LOCATION><![CDATA[江西 南昌]]></LOCATION><TBGOODSLINK><![CDATA[http://g.search1.alicdn.com/img/i1/11561066671082819/TB2LksfbFXXXXXqXpXXXXXXXXXX_!!14691561-0-saturn_solar.jpg_sum.jpg]]></TBGOODSLINK><WANGWANGID><![CDATA[csd95002]]></WANGWANGID><EURL><![CDATA[http://click.simba.taobao.com/cc_im?p=%E8%A1%A3%E6%9C%8D&s=2135489428&k=333&e=aSpSdg9wXGO9eKnI8Qxw97eQ2hU5ZYgQ5lwdVsY%2B2aiWuomdaoFvtkyZdLqYCqwezX0R2QUuvZ%2BL9m%2BfCblIw%2FfRzo1wbfq9Up9nQou4Bg%2BLz%2FiVK82QynlJc5%2BbT9SbzEF2wjJJtXZAHjqQ2xO8swPGnXALpeU1WdNiG1HcykH6gz9NIOtb%2FS8w3esqeTfKFnET4M8vTrSA87BtTucaRpBUlDZ0qXqB%2BVz2dz%2BsGsH2qIhPkyJZE1ihJwhg9k%2F2nv%2Bk8129jwP55ttUVe36LNmWeo0C8h9QmHlzN0J7o%2FaU4woafA9WAEBkZfuuhvrDsb54GVWX54s%3D]]></EURL><MATCHTYPE><![CDATA[7]]></MATCHTYPE><RANKSCORE><![CDATA[38879943480]]></RANKSCORE><FIRSTRANK><![CDATA[6352]]></FIRSTRANK><STATICSCORE><![CDATA[379]]></STATICSCORE><BIDSCORE><![CDATA[215999686]]></BIDSCORE><QUERYID><![CDATA[2]]></QUERYID><CP><![CDATA[179]]></CP><PRICE><![CDATA[180]]></PRICE><SELL><![CDATA[38]]></SELL><PROPERTYID><![CDATA[14031880,3226292,20307554,29445,3788929,29447,28320,3232480,132069,28341,28326,130164,28324,90554,28327,3232483,28338,28335,60092,28332,30156,3232481,3232478,3232482,28340,3232479,3232484,107121,80882,28329,3271530,3271531,3267945,3271533,3271537,3271540,3267781,129555,129555,110282734,3226292,3267162,105255,29457,14139135,3224795,42007,248572013,178914558]]></PROPERTYID><DISPLAY_RESOLUTION><![CDATA[80*80]]></DISPLAY_RESOLUTION><ISPREPAY><![CDATA[1]]></ISPREPAY><SUBTITLE><![CDATA[]]></SUBTITLE></PRESULT><PRESULT><ADGROUPID><![CDATA[192174918]]></ADGROUPID><BIDWORDID><![CDATA[53037787149]]></BIDWORDID><CAMPAIGNID><![CDATA[3247188]]></CAMPAIGNID><CREATIVEID><![CDATA[205322419]]></CREATIVEID><CUSTOMERID><![CDATA[1103034176]]></CUSTOMERID><DESC><![CDATA[]]></DESC><CLICKURL><![CDATA[http://item.taobao.com/item.htm?id=17921333262]]></CLICKURL><ST_KEYWORDS><![CDATA[男装短袖]]></ST_KEYWORDS><REDKEY><![CDATA[男装短袖]]></REDKEY><TITLE><![CDATA[3皇冠信誉!2014潮男士短袖T恤 3件55包邮!]]></TITLE><REDKEYS><![CDATA[琛 f 湇 ]]></REDKEYS><RESOURCEID><![CDATA[17921333262]]></RESOURCEID><GOODSPRICE><![CDATA[9800]]></GOODSPRICE><CATID><![CDATA[30,50000436]]></CATID><LOCATION><![CDATA[江西 南昌]]></LOCATION><TBGOODSLINK><![CDATA[http://g.search1.alicdn.com/img/i1/11561066671082819/TB2LksfbFXXXXXqXpXXXXXXXXXX_!!14691561-0-saturn_solar.jpg_sum.jpg]]></TBGOODSLINK><WANGWANGID><![CDATA[csd95002]]></WANGWANGID><EURL><![CDATA[http://click.simba.taobao.com/cc_im?p=%E8%A1%A3%E6%9C%8D&s=2135489428&k=333&e=aSpSdg9wXGO9eKnI8Qxw97eQ2hU5ZYgQ5lwdVsY%2B2aiWuomdaoFvtkyZdLqYCqwezX0R2QUuvZ%2BL9m%2BfCblIw%2FfRzo1wbfq9Up9nQou4Bg%2BLz%2FiVK82QynlJc5%2BbT9SbzEF2wjJJtXZAHjqQ2xO8swPGnXALpeU1WdNiG1HcykH6gz9NIOtb%2FS8w3esqeTfKFnET4M8vTrSA87BtTucaRpBUlDZ0qXqB%2BVz2dz%2BsGsH2qIhPkyJZE1ihJwhg9k%2F2nv%2Bk8129jwP55ttUVe36LNmWeo0C8h9QmHlzN0J7o%2FaU4woafA9WAEBkZfuuhvrDsb54GVWX54s%3D]]></EURL><MATCHTYPE><![CDATA[7]]></MATCHTYPE><RANKSCORE><![CDATA[38879943480]]></RANKSCORE><FIRSTRANK><![CDATA[6352]]></FIRSTRANK><STATICSCORE><![CDATA[379]]></STATICSCORE><BIDSCORE><![CDATA[215999686]]></BIDSCORE><QUERYID><![CDATA[2]]></QUERYID><CP><![CDATA[179]]></CP><PRICE><![CDATA[180]]></PRICE><SELL><![CDATA[38]]></SELL><PROPERTYID><![CDATA[14031880,3226292,20307554,29445,3788929,29447,28320,3232480,132069,28341,28326,130164,28324,90554,28327,3232483,28338,28335,60092,28332,30156,3232481,3232478,3232482,28340,3232479,3232484,107121,80882,28329,3271530,3271531,3267945,3271533,3271537,3271540,3267781,129555,129555,110282734,3226292,3267162,105255,29457,14139135,3224795,42007,248572013,178914558]]></PROPERTYID><DISPLAY_RESOLUTION><![CDATA[80*80]]></DISPLAY_RESOLUTION><ISPREPAY><![CDATA[1]]></ISPREPAY><SUBTITLE><![CDATA[]]></SUBTITLE></PRESULT></PRESULTS></QUERYRESULT>';
        logging::debug($string);
        $ret =  xml::xml2array($string);
        logging::info($ret);
        $title = mb_convert_encoding("3皇冠信誉!2014潮男士短袖T恤 3件55包邮!", "GBK");
        logging::debug(mb_detect_order(), mb_detect_encoding($title), mb_detect_encoding($ret['PRESULTS']['PRESULT'][0]['TITLE']));
        $this->assertEquals($ret['PRESULTS']['PRESULT'][0]['TITLE'], $title );

        $ads = xml::getAds($string);
        logging::info($ads);
        $this->assertEquals(count($ads), 1);

    }

    public function test_error() {
        $string = '<?xml version="1.0" encoding="GBK"?><QUERYRESULT><VERSION>-1</VERSION></QUERYRESULT>';
        $ret =  xml::xml2array($string);
        logging::info($ret);
        $this->assertEquals($ret['VERSION'], -1);

        $ads = xml::getAds($string);
        logging::info($ads);
        $this->assertEquals($ads, array());
    }

    public function test_ads() {
        $string = '<?xml version="1.0" encoding="GBK"?><QUERYRESULT><VERSION>2.1</VERSION><PTOTAL>600</PTOTAL><PTHIS>15</PTHIS><PPUSHLEFT>0</PPUSHLEFT><PRESULTS></PRESULTS><PRESULTS_D></PRESULTS_D><PRESULT></PRESULT></QUERYRESULT>';
        $ret =  xml::xml2array($string);
        logging::info($ret);
        $this->assertEquals($ret['PRESULTS'], array());
        $this->assertEquals($ret['PRESULTS_D'], array());

        $ads = xml::getAds($string);
        logging::info($ads);
        $this->assertEquals($ads, array());

        $string = "";
        try {
            $ret =  xml::xml2array($string);
            $this->assertTrue(False);
        } catch(TypeError $e) {
            $this->assertTrue(True);
        }
    }

    public function test_call() {
        $string = '';
        try {
            $this->xml->aaaa();
            $this->assertTrue(False);
        } catch (CallFunctionFail $e) {
            $this->assertTrue(True);
        }
        
    }

    public function test_xmlFromfile() {
        $string = dirname(__FILE__)."/test.xml"; 
        $ret =  xml::xml2array($string);
        logging::info($ret);   
        $title = $ret['PRESULTS']['PRESULT'][0]['TITLE'];
        logging::error(mb_detect_order(), mb_detect_encoding($title), mb_detect_encoding($ret['PRESULTS']['PRESULT'][0]['TITLE']));
        $this->assertEquals($ret['PRESULTS']['PRESULT'][0]['TITLE'], $title );
    }

    public function test_xmlFromUrl() {
        $string = 'http://fer1a1.kgb.et2.tbsite.net/?name=tbuad&count=5&q2cused=1&p4p=__p4p_sidebar__%2C__p4p_bottom__&keyword=%E8%A1%A3%E6%9C%8D&pid=420434_1006%2C420435_1006&sort=&ip=111.197.80.3&offset=16&rct=11&propertyid=&sbid=&o=x&frontcatid=50103037&t=1422374661268&com_acquired=vitalprop:13';
        $ret =  xml::xml2array($string);
        logging::info($ret);
        $title = $ret['PRESULTS']['PRESULT'][0]['TITLE'];
        logging::error(mb_detect_order(), mb_detect_encoding($title), mb_detect_encoding($ret['PRESULTS']['PRESULT'][0]['TITLE']));
        $this->assertEquals($ret['PRESULTS']['PRESULT'][0]['TITLE'], $title );
    }

    public function test_xmlFromfile1() {
        $string = dirname(__FILE__)."/person.proto";
        try {
            $ret =  xml::xml2array($string);
            $this->assertTrue(False);
        } catch (TypeError $e) {
            $this->assertTrue(True);
        }
    }

    public function test_xmlFromUrl1() {
        $url = "http://10.125.51.188:4893/job/basa/api/xml";
        $ret =  xml::xml2array($url);
        logging::info($ret);
        $this->assertEquals($ret["name"], "Basa");
    }

    public function test_array2xml() {
        $url = "http://10.125.51.188:4893/job/basa/api/xml";
        $string = file_get_contents($url);
        $ret = xml::xml2array($url);
        $tmp = xml::xml2array($url);
        logging::info($ret);

        $ret = xml::array2xml($ret, "freeStyleProject");
        logging::debug($ret, $string, gettype($ret));

        //file_put_contents("aaaa.xml", $string);
        //file_put_contents("bbbb.xml", $ret);
        $ret = xml::xml2array($ret);
        $this->assertEquals($tmp, $ret);
    }

    public function test_array2xml_err() {
        $obj = new logging();
        $ret = xml::array2xml($obj);
        $this->assertEquals(null, $ret);
    }

    public function tearDown() {
        unset($this->xml);
    }
}
?>
