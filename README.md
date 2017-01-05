<link href="http://gitlab.alibaba-inc.com/dongjiang.dongj/basa/wikis/markdown.css" rel="stylesheet"></link>
# 标准库使用文档（用户版）

> 持续更新中...

> 作者 易少 @dongjiang.dongj 

> 标准库需求和缺陷录入地址：

>> http://k3.alibaba-inc.com/issue?versionId=1080122

## 目录

   > [前言](#1) 

   > [安装与快速开始](#2) 

   >> [如何安装](#2-1)

   >> [如何开始使用](#2-2)

   > [如何使用到测试](#3)

   >> [标准库API](#3-0-api)

   >> [功能和样例](#3-1)

   >>> [用例日志处理](#3-1-1)
 
   >>> [shell命令执行](#3-1-2-shell)

   >>> [Text配置解析](#3-1-3-text)

   >>> [Yaml配置解析](#3-1-4-yaml)

   >>> [通用断言使用](#3-1-5)

   >>> [明文文件处理](#3-1-6)
  
   >>> [process相关操作](#3-1-7-process)

   >>> [html通用解析以及业务方法](#3-1-8-html)

   >>> [xml通用解析以及业务方法](#3-1-9-xml)

   >>> [通用json解析方法](#3-1-10-json)

   >>> [protobuf解析方法](#3-1-11-protobuf)

   >>> [通用http请求](#3-1-12-http)

   >>> [处理array方法类](#3-1-13-array)

   >>> [获取机器相关信息](#3-1-14)

   >>> [处理PvLOG格式日志](#3-1-15-pvlog)
   
   >>> [处理普通单行格式日志](#3-1-16)
   
   >>> [通用MOCK](#3-1-17-mock)
   
   >>>> [Httpserver Mock](#3-1-17-1-httpmockserver)

   >>>> [KFCServer Mock](#3-1-17-2-kfcmockserver)
   
   >>> [处理string方法类](#3-1-18-string)

   >>> [mysql通用方法](#3-1-19-mysql)

   >> [高级用法](#3-2)

   > [Todo's](#4-todo-39-s)

   > [License](#5-license)

## 1、前言
   为了让大家更好的了解使用标准库，推出一个使用版本的结合。让大家更好的使用标准库代码。

   - 安装和快速使用
   - 标准库级别对外功能
   - 标准库高级功能

## 2、安装与快速开始
### 2.1如何安装？
```sh
$ sudo yum install php-test-common -b current
```
或者


可以查看yum源上最新版本：

 [http://yum.corp.taobao.com/cgi-bin/yumsearch?name=php-test-common](http://yum.corp.taobao.com/cgi-bin/yumsearch?name=php-test-common)

### 2.2如何开始使用？
安装完包，应该是引入包进行使用了。

首先可以在你的php代码中引入
```php
<?php
require_once("/usr/local/lib64/basa/basa.php");  #引入php-test-common的基础包入口
?>
```

现在你可以使用php-test-common包中提供的各种magic功能了！

## 3、如何使用到测试？
### 3.0 标准库全局api

**API地址:**

http://10.125.51.188:4893/job/basa/Lib_Api_Report/nav.html?index.html

或者（看个人喜好）

http://10.125.51.188:4893/job/basa/PHPuml_Report/

**如不知道怎么找，请使用右上角search功能！！！** 

### 3.1 功能和样例
介绍标准库各个块功能，并给出使用场景样例。

#### 3.1.1 用例日志处理
新功能logging日志处理。

不仅可以给出所打印的日志，是代码中的默认打印出：`哪个文件：文件中的哪个类：类中的哪个方法：方法中的哪一行`。

>Formate:

>> [2014-12-9 3:07:19,123][INFO][test.php:testclass:testfunction:line]: XXX

而且可以设置所要打印的`日志等级`、所要打印的`日志输出方式`（文件、stdout）；

并且支持魔法传参， `支持打印中的各种类型数据`。


**最新效果**

```php
<?php
require_once("/usr/local/lib64/basa/basa.php");
# add you code
$ret = 'bbb';
$obj = new logging();
logging::info("result:", "aaa", null, false, "", $ret, $obj, array(1,23,4));
logging::debug('aa', "", 123);
logging::warn('bb');
logging::error("my error", 123);
logging::fatal('123');
?>
```

输出：
> ![logging 输出结果](http://gitlab.alibaba-inc.com/dongjiang.dongj/basa/wikis/logging.png)

```php
<?php
$log = new logging();
$log->setLevel(LOGTYPE::DEBUG);
$log->getLogger('aaaa.log');
?>
```

> `可是将日志输出log日志等级、和输出日志文件`

#### 3.1.2 shell命令执行
新shell执行模块，不仅支持本地机器或远程机器的执行；而且还支持输出状态和输出结果保存. 并会给出执行异常或错误日志提示.

`此功能是级别功能，在所有类中，都可以执行; 可以使用所有类对象去执行这个方法`。

##### 样例
```php
<?php
$obj = new Eobject($host="127.0.0.1");
$obj->run('ls -l'); #在指定$host上，执行ls -l
$obj->rcp('/home/dongjiang.dongj/tools/aaa', '/tmp/'); #将aaa文件拷贝/tmp/下
$obj->rcp('dongjiang.dongj:password@100.1.1.1:/home/dongjiang.dongj/tools/aaa', '/tmp/'); #将远程机器上aaa文件拷贝本机/tmp/下

$obj->execute("ls -l"); #在本机上执行shell命令

#返回的都是 array($status, $outputArray);
?>
```

#### 3.1.3 Text配置解析
解析 带有多层结构的ini格式文件 和 key-value格式文件. 实现文件的`增、删、改和获取`操作。

##### 样例
```php
<?php
$CM = new ConfigManager(); #全局生成一个ConfigManager实例
# array是需要的属性，test.conf是要处理的文件； 127.0.0.1是哪天机器上的配置；"" 是否使用本机配置替换；True是否需要配置回滚
$obj = $CM->getInstanceHandle("text", array("./test.conf", '127.0.0.1', "", True, True));  

$obj->set('key1', 'value1', 'key2', 'value2'); #设置 key-value对
$obj->get('key1'); #获取 key1的value数据

# 全局有两个关键词：section 和 sep，section表示在那个标签下的数据，sep 表示key与value中的分隔符，如：“：”、“=”或“=>”等等
$obj->set("key",'value1', 'key2', 'value2', 'sep', "=", 'section', 'abc'); #
$obj->get('key', '=', 'abc'); #获得abc section下，使用=区分的key的值

$obj->delete('key1'); #在现有的文件中删除key1

$obj->add('key1', 'value1'); #在现有的key1中，添加value

$obj->iset('key1', 'value_add'); #在以有的value中追加value
?>
```

#### 3.1.4 Yaml配置解析
解析 yaml 文件，实现文件的`增、删、改和获取`操作。

```php
<?php
$CM = new ConfigManager(); #全局生成一个ConfigManager实例
$obj = $CM->getInstanceHandle("yaml", array("test.yaml", '127.0.0.1', "", True));
$obj->set('A.B.C', array(1234,123));

#如果B.C是唯一的块，返回的就是array('A.B.C'=>array(1234, 123));
#如果B.C不是唯一块，返回的就是array('A.B.C'=>array(1234, 123), "XXXX.B.C.XXX"=>array(XXXXXX));
$obj->get('B.C');

$obj->delete('A.B.C'); #删除A.B.C块

$ret->has_key('a.b.c'); #是否有这块

$obj->add('A.B.C', 'value1'); #在现有的key1中，添加value

$obj->iset('A.B.C', 'value_add'); #在以有的value中追加value
?>
```


#### 3.1.5 通用断言使用
统一断言，解决case比较判断的逻辑。

```php
<?php
#比较期望和预期是否现实
$ret = asserts::assertEqual($expected=123, $actual="abc", $message = "Test Error");

#是否 预期 array是否升序
$ret = asserts::assertAscending($actualarr=array(1,2,3,4,5));

#还有其他，请查询API

#判断是否符合正则
$ret = asserts::assertRegExp('/abc|bcd/', "abcdefg");

#设置stdout输出
asserts::setMode(asserts::PrintError);

#设置异常输出, 默认为异常输出
asserts::setMode(asserts::ThrowExcept);

?>
```


#### 3.1.6 明文文件处理

```php
<?php
# array是需要的属性，aaaa是要处理的文件； 127.0.0.1是哪天机器上的配置；"" 是否使用本机配置替换；True是否需要配置回滚；True文件不存在，是否touch出一个新文件
$obj = new file("aaaa", '127.0.0.1', "", True, True));  

#获得文件全路径
$obj->fullname();

#获得文件所在目录
$obj->pathname();

#获得文件名
$obj->basename();

#获得文件大小
$obj->size();

#获得文件md5值
$obj->md5sum();

#清空文件
$obj->clear();

#获得文件行输
$obj->lines();

#用新数据重新写到文件
$obj->feed('aaaaaa');

#文件内容grep
$obj->grep('-o', '[A-Z].*');

#文件修改
$obj->sed("-i", "s#a#b#g");

#获得文件后多少行
$obj->tail("-n100");

$obj->head("-n100");

#文件另存为
$obj->saveto("bbbb");
?>
```


#### 3.1.7 process相关操作

此类是托管被测模块。管理被测模块的启停、是否活着、进程数、pid、ppid、占用内存 cpu的情况 以及 环境设置（core设置等）

```php
<?php

# "master"是模块启动的模块名; "/home/a/search/"是模块目录；"127.0.0.1"是模块所在机器；""是模块init本地备份环境；false：是否清空环境
$module = new Process("master", "/home/a/search/", "127.0.0.1", "", false);

$module->setCoreDumpControl("/tmp"); #设置core路径/tmp下，可以不填

# 业务代码
# 启动模块
$module->start("/home/a/search/bin/master -c /home/a/search/conf/master.conf start");
#...

#获得模块pid
$module->getPid();

#模块当前使用的物理内存(kbyte)
$module->getMem();

#判断当前模块是否还在
$module->is_Alive();

#获得模块当前启动的线程数
$module->getThreads();

#获得进程所占当前使用的虚拟内存(kbyte)
$module->getVmem();

#获取进程名
$module->getProc();

# 获得子进程列表方法
$module->getCpid();

#停模块
$module->stop("/home/a/search/bin/master -c /home/a/search/conf/master.conf stop");

$module->isCoreDumped(); #判断是否有coredump
$module->cleanCore(); #清空系统core文件

?>
```

#### 3.1.8 html通用解析以及业务方法

通用解析html2array方法，将一根html解析到simple_html_dom的一个对象。
并且实现了引擎o=d中，广告解析和pvlog的解析

```php
<?php

#将html解析成一个array，输入参数可以是 url、可以是html的string；也可以是html的文件
$ret = Html::html2array("http://www.baidu.com");
$ret = Html::html2array("<head></head>");
$ret = Html::html2array("filanem.html");

#将html解析成一个obj（simple_html_dom的一个对象），入参数可以是 url、可以是html的string；也可以是html的文件
$ret = Html::html2obj("http://www.baidu.com");
$ret = Html::html2obj("<head></head>");
$ret = Html::html2obj("filanem.html");

#业务方法
$string = 'http://fer1a1.kgb.et2.tbsite.net/?name=tbuad&count=5&q2cused=1&p4p=__p4p_sidebar__%2C__p4p_bottom__&keyword=%E8%A1%A3%E6%9C%8D&pid=420434_1006%2C420435_1006&sort=&ip=111.197.80.3&offset=16&rct=11&propertyid=&sbid=&o=d&frontcatid=50103037&t=1422374661268&com_acquired=vitalprop:13';

#获取 o=d 接口中的广告数据，返回时一个array；入参数可以是 url、可以是html的string；也可以是html的文件
$ret =  Html::getTable($string);

#获得o=d接口中的pv日志，返回时一个string；入参数可以是 url、可以是html的string；也可以是html的文件
$ret =  Html::PVlog($string);

?>
```

#### 3.1.9 xml通用解析以及业务方法
通用的xml协议解析方法。将xml解析成array。

```php
<?php

#通用的将xml解析成array，输入参数可以是 url、可以是html的string；也可以是html的文件
$ret =  xml::xml2array('<?xml version="1.0" encoding="GBK"?><VERSION>2.1</VERSION>');
$ret =  xml::xml2array('test.xml');
$ret =  xml::xml2array('http://10.125.51.188:4893/job/basa/api/xml');

#业务方法
$ads = xml::getAds("<?xml version='1.0' encoding='GBK'?><VERSION>2.1</VERSION>");

?>
```

#### 3.1.10 通用json解析方法
通用json解析方法，实现序列化和反序列化方法.

```php
<?php

#json 序列化：可以将array序列化为json string
$arr = array("a"=>"b");
$jsonstring = Json::Serialize($arr); # {"a":"b"}

#json 反序列化：可以将json string 反序列化为array
$string = '{"a":"b"}';
$arr = Json::Deserialize($string); # array("a"=>"b")

?>
```

#### 3.1.11 protobuf解析方法
protobuf协议：实现简单的将array转 pb二进制；将json string转pb二进制；

也可以将反序列化： 将pb 二进制数据转成 array或者json string

```php
<?php


#将array序列化为pb二进制数据
$base_array = array("name"=>"aa", "id"=>123, "ok"=>array(1,2,3), "pt"=>array(0,1,2), "phone"=>array(array("aaa"=>"aaaaaa", "bb"=>12111)));
#说明：第一个参数为array数据；第二个参数使用对应解析的proto配置；第三个参数是需要解析的message 类名； 第四个参数是中间文件输出地址，可以不填，默认生成到/tmp下面
$string = pb::Serialize($base_array, dirname(__FILE__)."/person.proto", "Person", dirname(__FILE__));



##将json string 序列化为 pb二进制数据
$json = '{"name":"aaa","id":1,"email":"dongjiang.dongj@test.com","ok":["1","2","3","4","5","6","7"],"pt":[1,1,1,1,2],"phone":[{"aaa":"aaaaaa","bb":0}]}';
$string = pb::SerializeFromJson($json, dirname(__FILE__)."/person.proto", "Person"); #四个参数使用默认


##反序列化：将pb二进制 转 array
#说明：第一个参数为pb二进制数据；第二个参数使用对应解析的proto配置；第三个参数是需要解析的message 类名； 第四个参数是中间文件输出地址，可以不填，默认生成到/tmp下面
$arr = pb::Deserialize("adfasdfwqef", dirname(__FILE__)."/person.proto", "Person");

$myjson = pb::DeserializeToJson("adfasdfwqef", dirname(__FILE__)."/person.proto", "Person");

?>
```

> *proto文件格式要求*

>> 1、要求将proto引用的所有proto格式写在一个proto中

>> 2、要求proto中的message 对象，必须先声明、再引用

>> 3、去掉proto文件中的 import、package等C或java的关键词，php没有解析这些


#### 3.1.12 通用http请求

获取http协议结果

```php
<?php

#获取到http协议结果, get 请求
$ret = Http::get("http://fer1a1.test.kgb.et2.tbsite.net/?name=tbuad&count=5&q2cused=1&p4p=__p4p_sidebar__%2C__p4p_bottom__&keyword=%E8%A1%A3%E6%9C%8D&pid=420434_1006%2C420435_1006&sort=&ip=111.197.80.3&offset=16&rct=11&propertyid=&sbid=&o=d&frontcatid=50103037&t=1422374661268&com_acquired=vitalprop:13");

#获得 post请求
$ret = Http::post("http://www.alibaba-inc.com");

#可以自定义设置proxy
Http::setProxy("127.0.0.1:8000"); #设置后，全局生效

?>
```


#### 3.1.13 处理array方法类
集成各个页面模块中，所用到的array处理方法。

```php
<?php

#获取数组中对应key的value，没有时返回默认值
$ret = Uarray::getArrValuebyKey(array("a"=>"b"),"","aaa"); #"aaa"
$ret = Uarray::getArrValuebyKey(array("a"=>"b"),"a","aaa"); #"b"

#返回数组中key以特定字符开头的数组内容，并对这些key进行重写生成新的数组 (key中打头的内容去掉)
$ret = Uarray::getArraybyPreKey(array("aa"),"0"); #array("aa")
$ret = Uarray::getArraybyPreKey(array("a"=>"aa"),"0"); #array()
$ret = Uarray::getArraybyPreKey(array("a"=>"aa"),"a"); #array(""=>"aa")

?>
```


#### 3.1.14 获取机器相关信息

获取机器相关信息。

```php
<?php

#获得机器ip
$ret = Machine::getIp(); #10.192.119.188

#获得机器名称
$ret = Machine::getHostname(); # v125051188.bja
?>
```
#### 3.1.15 处理PvLOG格式日志

 处理PvLOG格式日志方法

```php
<?php
#全局生成Log Manager
$LM = new LogManager();

# 获得pvlog.log的文件
# array是需要的属性，pvlog.log是要处理的文件； 127.0.0.1是哪天机器上的配置；"" 是否使用本机配置替换；True是否需要配置回滚; True如果文件>
不存在的时候，是否touch出一个空文件
$Obj = $CM->getInstanceHandle("PVLog", array(dirname(__FILE__)."/pvlog.log", "127.0.0.1", "", true, True));

#获得文件的当前行数
$Obj->line();

#判断pvlog是否滚动
$ret = $Obj->isroll();

#获取当前的PVLOG
$obj = $Obj->get();

#seek查询判断pvlog中是否存在指定正则,支持一个正则
$obj = $Obj->seek("[.*]");

#查询是否有制定字段. 判断pvlog中是否存在指定内容(可以多值),并返回匹配到的解析后的pvlogarr
$obj = $Obj->search();

# 判断pvlog中是否存在指定内容，多个传参，之间为或的关系
$obj = $Obj->isexist();
?>
```

#### 3.1.16 处理普通单行格式日志

处理普通单行格式日志相关方法

```php
<?php
#全局生成Log Manager
$LM = new LogManager();

# array是需要的属性，master.log是要处理的文件； 127.0.0.1是哪天机器上的配置；"" 是否使用本机配置替换；True是否需要配置回滚; True如果文件>
不存在的时候，是否touch出一个空文件
$Obj = $CM->getInstanceHandle("Single", array(dirname(__FILE__)."/master.log", "127.0.0.1", "", true, True));

#获得文件的当前行数
$Obj->line();

#判断日志是否滚动
$ret = $Obj->isroll();

#获取日志内容
$obj = $Obj->get();

#seek查询判断日志中是否存在指定正则,支持一个正则
$obj = $Obj->seek("[DEBUG]"); #获得Debug相关日志

#查询是否有制定字段.
$obj = $Obj->search();

# 判断日志中是否存在指定内容，多个传参，之间为或的关系
$obj = $Obj->isexist();
?>
```

#### 3.1.17 通用Mock
   
    为了解决开发与测试对mock的业务绑定，通过通用Mock，使用用户自定义callback的方式，实现轻量级、稳定、并且通用的Mock。
    
    现在已经对Http、Kfc进了mock

##### 3.1.17.1 HttpMockServer

通用 HttpMockServer mock

```php
<?php
$MM = new MockManager(); #全局生成一个MockManager实例
# array是需要的属性，Mockname1是索要启动的mockserver的name；"5":是本个mock所启动的进程个数； 127.0.0.1是启动的domain；"3349"：所要启动的端口
$obj = $MM->getInstanceHandle("HttpMockServer", array("Mockname1", 5, '127.0.0.1', "3349"));
$obj2 = $MM->getInstanceHandle("HttpMockServer", array("Mockname2", 5, '127.0.0.1', "3359"));

#start前设置callback 回掉方法。 其中$connection是一次连接对象，$data 是http请求url
$obj->callback(
    function($connection, $data){
        HttpProtocol::header('HTTP/1.1 200');
        return $connection->send('{"aaa":"bbb"}');
    }
);

#启动mock
$obj->start();

#业务逻辑, 使用geturl方法获得可以请求的url地址
http::get($obj->geturl());  #http::post($obj->geturl());

#业务逻辑, 使用geturl方法获得可以请求的url地址
$obj->getPort(); #获得mock服务的port，由于用户提供的port可能占用，随机分配一个端口
$obj->getDomain(); #得mock服务的domain, 于用户提供的可能是"127.0.0.1"或者为机器名，会转换成机器真实IP


#停止mock
$obj->stop();
?>
```

##### 3.1.17.2 KfcMockServer

通用 KfcMockServer mock

前提kfc服务是不可少的。KfcMockServer mock的是业务的服务

```php
<?php
$MM = new MockManager(); #全局生成一个MockManager实例
# array是需要的属性，Mockname1是索要启动的mockserver的name；"5":是本个mock所启动的进程个数； "group2"是kfc服务的group；"/tmp/file.sock"：kfc服务的sock文件全路径
$obj = $MM->getInstanceHandle("KfcMockServer", array("Mockname1", 5, 'group2', "/tmp/file.sock"));
$obj2 = $MM->getInstanceHandle("KfcMockServer", array("Mockname2", 5, 'group1', "/tmp/file.sock"));

#start前设置callback 回掉方法。 其中$buf是需要mock返回的string，$input 是request请求string
$obj->callback(
    function($input, &$buf) {
            if ($input == 1)
                $buf = "aaa";
            else if ($input == 2)
                $buf = "bbb";
            else if ($input == 3)
                $buf = "ccc";
            else
                $buf = "ddd";
    }
);


#启动mock
$obj->start();

# 业务相关，这个时候可以使用mock了
$obj->getGroup();  #获得group 组
$obj->getSock();  #获得 sock文件string

#可以通过一下方法获得，kfc的数据了
static function getdataforkfc($group, $sock, $input) {
        $ka = kfc_joingroup($group, $sock);
        $res = kfc_sendmsg($ka, $input, KFC_ASYNC, 20000);
        $msg = kfc_recvmsg($ka, KFC_ASYNC, 20000);
        kfc_leavegroup($ka);
        return $msg;
}

$ret_data = getdataforkfc($obj->getGroup(), $obj->getSock(), "1");

#停止mock
$obj->stop();
?>
```

#### 3.1.18 处理string方法类

#### 3.1.19 mysql通用方法


### 3.2 高级用法（自我扩展库）
添加自己的方法。
   填自己的conf、log、dict plugin.

比如： 标准库，有不满足你需要的，

#### 第一，提需求：

**标准库需求和缺陷录入地址：**

**http://k3.alibaba-inc.com/issue?versionId=1080122**

#### 其次：支持公告接口，自我实现接口：

实现方式：

```php
<?php
#实现对应接口、并继承file方法
class XXXX extends file implements ConfIPlugin/LogIPlugin/DictIPlugin {
    const MODE = "yaml";
    const CLASSNAME = __CLASS__;

    public function set();

    public function get();

    #......

}
?>
```

## 4、Todo's

 - mock 相关
 - string 相关
 - mysql 相关

## 5、License
----

Developer:

> @dongjiang.dongj @linhan @hanxuan.zxx @qigang.llb @qiuhua.lqh @wanling.dx 


# 有问题，请联系：

> 易少 @dongjiang.dongj

**Free Software, Hell Yeah!**
