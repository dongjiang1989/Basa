<?php
/*====================================================
*   Copyright (C) 2014 All rights reserved.
*
*   Filename      : basa.php
*   Author        : dongjiang.dongj@alibaba-inc.com
*   Last modified : 2014-12-03 10:13
*   Description : 
======================================================*/
declare(encoding='UTF-8');

/**
* 全局库入口
* @author dongjiang.dongj
*/
require_once(dirname(__FILE__)."/com/util.php");
require_once(dirname(__FILE__)."/confmanager.php");
require_once(dirname(__FILE__)."/logmanager.php");
require_once(dirname(__FILE__)."/dictmanager.php");
require_once(dirname(__FILE__)."/mockmanager.php");
require_once(dirname(__FILE__)."/asserts.php");
require_once(dirname(__FILE__)."/process.php");
require_once(dirname(__FILE__)."/machine.php");
require_once(dirname(__FILE__)."/uarray.php");
require_once(dirname(__FILE__)."/ustring.php");
require_once(dirname(__FILE__)."/request.php");
require_once(dirname(__FILE__)."/util/json/Json.php");
require_once(dirname(__FILE__)."/util/protobuf/pb.php");
require_once(dirname(__FILE__)."/util/protocol/http.php");
require_once(dirname(__FILE__)."/util/protocol/swift.php");
require_once(dirname(__FILE__)."/util/html/html.php");
require_once(dirname(__FILE__)."/util/xml/xml.php");
