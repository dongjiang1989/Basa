<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../src/com/logging.php");
require_once(dirname(__FILE__)."/../src/util/html/html.php");
class TestHTML extends PHPUnit_Framework_TestCase {
    private $html;
    public function setup() {
        $this->html = new Html();
    }

    public function test_html2array() {
        $string = '<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
<meta name="renderer" content="webkit" />
</head>
<body class="qa-question">
<div class="global-nav">
    <nav class="container nav">
        <div class="dropdown m-menu">
            <a href="javascript:void(0);" id="dLabel" class="visible-xs-block m-toptools" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="glyphicon glyphicon-align-justify"></span>
                <span class="mobile-menu__unreadpoint"></span>
            </a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                <li class="mobile-menu__item"><a href="/">问答</a></li>
                <li class="mobile-menu__item"><a href="/blogs">文章</a></li>
                <li class="mobile-menu__item"><a href="/events">活动</a></li>
                <li class="mobile-menu__item"><a href="/tags">标签</a></li>
                <li class="mobile-menu__item"><a href="/users">榜单</a></li>
                <li class="mobile-menu__item"><a href="/sites">子站</a></li>
                            </ul>
        </div>

        <h1 class="logo"><a href="/">SegmentFault</a></h1>

                <a href="/user/login" class="visible-xs-block pull-right m-ask m-toptools"><span class="glyphicon glyphicon-log-in"></span></a>
        
        <form action="/search" class="header-search pull-left hidden-sm hidden-xs">
            <button type="submit" class="btn btn-link"><span class="sr-only">搜索</span><span class="glyphicon glyphicon-search"></span></button>
            <input id="searchBox" name="q" type="text" placeholder="输入关键字搜索" class="form-control" value="">
        </form>

        <ul class="menu list-inline pull-left hidden-xs">
            <li class="menu__item"><a href="/">问答</a></li>
            <li class="menu__item"><a href="/blogs">文章</a></li>
            <li class="menu__item"><a href="/events">活动</a></li>
            <li class="menu__item"><a href="/tags">标签</a></li>
            <li class="menu__item"><a href="/users">榜单</a></li>
            <li class="menu__item dropdown hoverDropdown">
                <a data-toggle="dropdown" href="/sites" class="more dropdownBtn">
                    &middot;&middot;&middot;<span class="sr-only">更多</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/sites">子站</a></li>
                                    </ul>
            </li>
        </ul>

    </nav>
</div>
</body>';

        logging::debug($string);
        $ret =  Html::html2array($string);

        $this->assertEquals('array', gettype($ret));
        $string = 'http://fer1a1.kgb.et2.tbsite.net/?name=tbuad&count=5&q2cused=1&p4p=__p4p_sidebar__%2C__p4p_bottom__&keyword=%E8%A1%A3%E6%9C%8D&pid=420434_1006%2C420435_1006&sort=&ip=111.197.80.3&offset=16&rct=11&propertyid=&sbid=&o=d&frontcatid=50103037&t=1422374661268&com_acquired=vitalprop:13';
        $ret =  Html::html2array($string);
        $this->assertEquals('array', gettype($ret));

        $ret =  Html::html2array(dirname(__FILE__)."/test.html");
        $this->assertEquals('array', gettype($ret));

    }
    
    public function test_html2obj() {
        $string = '<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
<meta name="renderer" content="webkit" />
</head>
<body class="qa-question">
<div class="global-nav">
    <nav class="container nav">
        <div class="dropdown m-menu">
            <a href="javascript:void(0);" id="dLabel" class="visible-xs-block m-toptools" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="glyphicon glyphicon-align-justify"></span>
                <span class="mobile-menu__unreadpoint"></span>
            </a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                <li class="mobile-menu__item"><a href="/">问答</a></li>
                <li class="mobile-menu__item"><a href="/blogs">文章</a></li>
                <li class="mobile-menu__item"><a href="/events">活动</a></li>
                <li class="mobile-menu__item"><a href="/tags">标签</a></li>
                <li class="mobile-menu__item"><a href="/users">榜单</a></li>
                <li class="mobile-menu__item"><a href="/sites">子站</a></li>
                            </ul>
        </div>

        <h1 class="logo"><a href="/">SegmentFault</a></h1>

                <a href="/user/login" class="visible-xs-block pull-right m-ask m-toptools"><span class="glyphicon glyphicon-log-in"></span></a>
        
        <form action="/search" class="header-search pull-left hidden-sm hidden-xs">
            <button type="submit" class="btn btn-link"><span class="sr-only">搜索</span><span class="glyphicon glyphicon-search"></span></button>
            <input id="searchBox" name="q" type="text" placeholder="输入关键字搜索" class="form-control" value="">
        </form>

        <ul class="menu list-inline pull-left hidden-xs">
            <li class="menu__item"><a href="/">问答</a></li>
            <li class="menu__item"><a href="/blogs">文章</a></li>
            <li class="menu__item"><a href="/events">活动</a></li>
            <li class="menu__item"><a href="/tags">标签</a></li>
            <li class="menu__item"><a href="/users">榜单</a></li>
            <li class="menu__item dropdown hoverDropdown">
                <a data-toggle="dropdown" href="/sites" class="more dropdownBtn">
                    &middot;&middot;&middot;<span class="sr-only">更多</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/sites">子站</a></li>
                                    </ul>
            </li>
        </ul>

    </nav>
</div>
</body>';

        logging::debug($string);
        $ret =  Html::html2obj($string);

        $this->assertEquals('object', gettype($ret));
        $string = 'http://fer1a1.kgb.et2.tbsite.net/?name=tbuad&count=5&q2cused=1&p4p=__p4p_sidebar__%2C__p4p_bottom__&keyword=%E8%A1%A3%E6%9C%8D&pid=420434_1006%2C420435_1006&sort=&ip=111.197.80.3&offset=16&rct=11&propertyid=&sbid=&o=d&frontcatid=50103037&t=1422374661268&com_acquired=vitalprop:13';
        $ret =  Html::html2obj($string);
        $this->assertEquals('object', gettype($ret));

        $ret =  Html::html2obj(dirname(__FILE__)."/test.html");
        $this->assertEquals('object', gettype($ret));

    }

    public function test_getTable() {
        $string = 'http://fer1a1.kgb.et2.tbsite.net/?name=tbuad&count=5&q2cused=1&p4p=__p4p_sidebar__%2C__p4p_bottom__&keyword=%E8%A1%A3%E6%9C%8D&pid=420434_1006%2C420435_1006&sort=&ip=111.197.80.3&offset=16&rct=11&propertyid=&sbid=&o=d&frontcatid=50103037&t=1422374661268&com_acquired=vitalprop:13';
        $ret =  Html::getTable($string);
        logging::info($ret);
        $this->assertTrue(count($ret[1]) > 0);

        $string = "http://k2fer1a1.mob.et2.tbsite.net/?name=itemjump&pid=419095_1006&p4p=p4presult1&count=10&o=d&ac=chenchenac1&nickname=chenchennick1&elemtid=1&ip=10.232.131.80";
        $ret =  Html::getTable($string);
        logging::info($ret);

        $this->assertTrue(count($ret[1]) > 0);
    }

    public function test_getPVLOG() {
        $string = "http://k2fer1a1.mob.et2.tbsite.net/?name=itemjump&pid=419095_1006&p4p=p4presult1&count=10&o=d&ac=chenchenac1&nickname=chenchennick1&elemtid=1&ip=10.232.131.80";
        $ret =  Html::PVlog($string);
        logging::info($ret);
        $this->assertTrue($ret != "");
    }

    public function test_getPVLOG_empty() {
        $string = "http://fer1a1.test.kgb.et2.tbsite.net/?name=tbuad&count=5&q2cused=1&p4p=__p4p_sidebar__%2C__p4p_bottom__&keyword=%E8%A1%A3%E6%9C%8D&pid=420434_1006%2C420435_1006&sort=&ip=111.197.80.3&offset=16&rct=11&propertyid=&sbid=&o=d&frontcatid=50103037&t=1422374661268&com_acquired=vitalprop:10";
        $ret =  Html::PVlog($string);
        logging::info($ret);
        $this->assertTrue($ret == "");
    }
    
    public function test_html2array2() {
        $string = "http://fer1a1.test.kgb.et2.tbsite.net/?name=tbuad&count=5&q2cused=1&p4p=__p4p_sidebar__%2C__p4p_bottom__&keyword=%E8%A1%A3%E6%9C%8D&pid=420434_1006%2C420435_1006&sort=&ip=111.197.80.3&offset=16&rct=11&propertyid=&sbid=&o=d&frontcatid=50103037&t=1422374661268&com_acquired=vitalprop:10";
        $ret =  Html::html2array($string);
        $this->assertEquals(gettype($ret), 'array');
        $ret =  Html::getTable($string);
        logging::info($ret);
        $this->assertEquals(gettype($ret), 'array');
        try {
            $this->html->aaaa();
            $this->assertTrue(FALSE);
        } catch (CallFunctionFail $e) {
            $this->assertTrue(TRUE);
        }
    }

    public function tearDown() {
        unset($this->html);
    }
}
?>
