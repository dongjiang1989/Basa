<?php
require_once('/home/dongjiang.dongj/Source/basa/src/tools/protobuf/parser/../message/pb_message.php');
class PriceStatus extends PBEnum
{
  const BID  = 0;
  const PREMIUM  = 1;
}
class MessageType extends PBEnum
{
  const ADD  = 0;
  const UPDATE  = 1;
  const DELETE  = 2;
}
class CampaignTag extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "refType";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "value";
    $this->fields["3"] = "PBString";
    $this->values["3"] = "";
    $this->names_type["4"] = "priceModule";
    $this->fields["4"] = "PriceStatus";
    $this->values["4"] = "";
    $this->names_type["5"] = "bidPrice";
    $this->fields["5"] = "PBInt";
    $this->values["5"] = "";
    $this->names_type["6"] = "discount";
    $this->fields["6"] = "PBInt";
    $this->values["6"] = "";
    $this->names_type["7"] = "onlineStatus";
    $this->fields["7"] = "PBInt";
    $this->values["7"] = "";
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function refType()
  {
    return $this->_get_value("2");
  }
  function set_refType($value)
  {
    return $this->_set_value("2", $value);
  }
  function value()
  {
    return $this->_get_value("3");
  }
  function set_value($value)
  {
    return $this->_set_value("3", $value);
  }
  function priceModule()
  {
    return $this->_get_value("4");
  }
  function set_priceModule($value)
  {
    return $this->_set_value("4", $value);
  }
  function bidPrice()
  {
    return $this->_get_value("5");
  }
  function set_bidPrice($value)
  {
    return $this->_set_value("5", $value);
  }
  function discount()
  {
    return $this->_get_value("6");
  }
  function set_discount($value)
  {
    return $this->_set_value("6", $value);
  }
  function onlineStatus()
  {
    return $this->_get_value("7");
  }
  function set_onlineStatus($value)
  {
    return $this->_set_value("7", $value);
  }
}
class Pair extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "key";
    $this->fields["1"] = "PBString";
    $this->values["1"] = "";
    $this->names_type["2"] = "value";
    $this->fields["2"] = "PBString";
    $this->values["2"] = "";
  }
  function key()
  {
    return $this->_get_value("1");
  }
  function set_key($value)
  {
    return $this->_set_value("1", $value);
  }
  function value()
  {
    return $this->_get_value("2");
  }
  function set_value($value)
  {
    return $this->_set_value("2", $value);
  }
}
class SimbaMember extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "memberId";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "custId";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["4"] = "userId";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->names_type["5"] = "shopId";
    $this->fields["5"] = "PBInt";
    $this->values["5"] = "";
    $this->names_type["6"] = "name";
    $this->fields["6"] = "PBString";
    $this->values["6"] = "";
    $this->names_type["7"] = "location";
    $this->fields["7"] = "PBString";
    $this->values["7"] = "";
    $this->names_type["8"] = "im";
    $this->fields["8"] = "PBString";
    $this->values["8"] = "";
    $this->names_type["9"] = "nickname";
    $this->fields["9"] = "PBString";
    $this->values["9"] = "";
    $this->names_type["10"] = "phone";
    $this->fields["10"] = "PBString";
    $this->values["10"] = "";
    $this->names_type["11"] = "email";
    $this->fields["11"] = "PBString";
    $this->values["11"] = "";
    $this->names_type["12"] = "payedFlag";
    $this->fields["12"] = "PBInt";
    $this->values["12"] = "";
    $this->names_type["13"] = "custStatus";
    $this->fields["13"] = "PBInt";
    $this->values["13"] = "";
    $this->names_type["14"] = "outsideKey";
    $this->fields["14"] = "PBString";
    $this->values["14"] = "";
    $this->names_type["15"] = "sellerSum";
    $this->fields["15"] = "PBInt";
    $this->values["15"] = "";
    $this->names_type["16"] = "goodRate";
    $this->fields["16"] = "PBInt";
    $this->values["16"] = "";
    $this->names_type["17"] = "storeCategory";
    $this->fields["17"] = "PBString";
    $this->values["17"] = "";
    $this->names_type["18"] = "isGlobal";
    $this->fields["18"] = "PBInt";
    $this->values["18"] = "";
    $this->names_type["19"] = "extension";
    $this->fields["19"] = "PBString";
    $this->values["19"] = "";
    $this->names_type["20"] = "promotedType";
    $this->fields["20"] = "PBInt";
    $this->values["20"] = "";
    $this->names_type["21"] = "isHk";
    $this->fields["21"] = "PBInt";
    $this->values["21"] = "";
    $this->names_type["22"] = "isTaoBaoMall";
    $this->fields["22"] = "PBInt";
    $this->values["22"] = "";
    $this->names_type["23"] = "isLoveActivity";
    $this->fields["23"] = "PBInt";
    $this->values["23"] = "";
    $this->names_type["24"] = "isPayProtect";
    $this->fields["24"] = "PBInt";
    $this->values["24"] = "";
    $this->names_type["25"] = "isHotShop";
    $this->fields["25"] = "PBInt";
    $this->values["25"] = "";
    $this->names_type["26"] = "sellerType";
    $this->fields["26"] = "PBInt";
    $this->values["26"] = "";
    $this->names_type["27"] = "shopDomain";
    $this->fields["27"] = "PBString";
    $this->values["27"] = "";
    $this->names_type["28"] = "shopName";
    $this->fields["28"] = "PBString";
    $this->values["28"] = "";
    $this->names_type["29"] = "shopCatid";
    $this->fields["29"] = "PBString";
    $this->values["29"] = "";
    $this->names_type["30"] = "shopCatName";
    $this->fields["30"] = "PBString";
    $this->values["30"] = "";
    $this->names_type["31"] = "shopExtension";
    $this->fields["31"] = "PBString";
    $this->values["31"] = "";
    $this->names_type["1001"] = "update_time";
    $this->fields["1001"] = "PBInt";
    $this->values["1001"] = "";
    $this->names_type["1002"] = "message_id";
    $this->fields["1002"] = "PBInt";
    $this->values["1002"] = "";
    $this->names_type["1003"] = "message_type";
    $this->fields["1003"] = "MessageType";
    $this->values["1003"] = "";
  }
  function memberId()
  {
    return $this->_get_value("1");
  }
  function set_memberId($value)
  {
    return $this->_set_value("1", $value);
  }
  function custId()
  {
    return $this->_get_value("2");
  }
  function set_custId($value)
  {
    return $this->_set_value("2", $value);
  }
  function userId()
  {
    return $this->_get_value("4");
  }
  function set_userId($value)
  {
    return $this->_set_value("4", $value);
  }
  function shopId()
  {
    return $this->_get_value("5");
  }
  function set_shopId($value)
  {
    return $this->_set_value("5", $value);
  }
  function name()
  {
    return $this->_get_value("6");
  }
  function set_name($value)
  {
    return $this->_set_value("6", $value);
  }
  function location()
  {
    return $this->_get_value("7");
  }
  function set_location($value)
  {
    return $this->_set_value("7", $value);
  }
  function im()
  {
    return $this->_get_value("8");
  }
  function set_im($value)
  {
    return $this->_set_value("8", $value);
  }
  function nickname()
  {
    return $this->_get_value("9");
  }
  function set_nickname($value)
  {
    return $this->_set_value("9", $value);
  }
  function phone()
  {
    return $this->_get_value("10");
  }
  function set_phone($value)
  {
    return $this->_set_value("10", $value);
  }
  function email()
  {
    return $this->_get_value("11");
  }
  function set_email($value)
  {
    return $this->_set_value("11", $value);
  }
  function payedFlag()
  {
    return $this->_get_value("12");
  }
  function set_payedFlag($value)
  {
    return $this->_set_value("12", $value);
  }
  function custStatus()
  {
    return $this->_get_value("13");
  }
  function set_custStatus($value)
  {
    return $this->_set_value("13", $value);
  }
  function outsideKey()
  {
    return $this->_get_value("14");
  }
  function set_outsideKey($value)
  {
    return $this->_set_value("14", $value);
  }
  function sellerSum()
  {
    return $this->_get_value("15");
  }
  function set_sellerSum($value)
  {
    return $this->_set_value("15", $value);
  }
  function goodRate()
  {
    return $this->_get_value("16");
  }
  function set_goodRate($value)
  {
    return $this->_set_value("16", $value);
  }
  function storeCategory()
  {
    return $this->_get_value("17");
  }
  function set_storeCategory($value)
  {
    return $this->_set_value("17", $value);
  }
  function isGlobal()
  {
    return $this->_get_value("18");
  }
  function set_isGlobal($value)
  {
    return $this->_set_value("18", $value);
  }
  function extension()
  {
    return $this->_get_value("19");
  }
  function set_extension($value)
  {
    return $this->_set_value("19", $value);
  }
  function promotedType()
  {
    return $this->_get_value("20");
  }
  function set_promotedType($value)
  {
    return $this->_set_value("20", $value);
  }
  function isHk()
  {
    return $this->_get_value("21");
  }
  function set_isHk($value)
  {
    return $this->_set_value("21", $value);
  }
  function isTaoBaoMall()
  {
    return $this->_get_value("22");
  }
  function set_isTaoBaoMall($value)
  {
    return $this->_set_value("22", $value);
  }
  function isLoveActivity()
  {
    return $this->_get_value("23");
  }
  function set_isLoveActivity($value)
  {
    return $this->_set_value("23", $value);
  }
  function isPayProtect()
  {
    return $this->_get_value("24");
  }
  function set_isPayProtect($value)
  {
    return $this->_set_value("24", $value);
  }
  function isHotShop()
  {
    return $this->_get_value("25");
  }
  function set_isHotShop($value)
  {
    return $this->_set_value("25", $value);
  }
  function sellerType()
  {
    return $this->_get_value("26");
  }
  function set_sellerType($value)
  {
    return $this->_set_value("26", $value);
  }
  function shopDomain()
  {
    return $this->_get_value("27");
  }
  function set_shopDomain($value)
  {
    return $this->_set_value("27", $value);
  }
  function shopName()
  {
    return $this->_get_value("28");
  }
  function set_shopName($value)
  {
    return $this->_set_value("28", $value);
  }
  function shopCatid()
  {
    return $this->_get_value("29");
  }
  function set_shopCatid($value)
  {
    return $this->_set_value("29", $value);
  }
  function shopCatName()
  {
    return $this->_get_value("30");
  }
  function set_shopCatName($value)
  {
    return $this->_set_value("30", $value);
  }
  function shopExtension()
  {
    return $this->_get_value("31");
  }
  function set_shopExtension($value)
  {
    return $this->_set_value("31", $value);
  }
  function update_time()
  {
    return $this->_get_value("1001");
  }
  function set_update_time($value)
  {
    return $this->_set_value("1001", $value);
  }
  function message_id()
  {
    return $this->_get_value("1002");
  }
  function set_message_id($value)
  {
    return $this->_set_value("1002", $value);
  }
  function message_type()
  {
    return $this->_get_value("1003");
  }
  function set_message_type($value)
  {
    return $this->_set_value("1003", $value);
  }
}
class SimbaCampaign extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "custId";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "memberId";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->names_type["4"] = "productLineId";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->names_type["5"] = "type";
    $this->fields["5"] = "PBInt";
    $this->values["5"] = "";
    $this->names_type["6"] = "startTime";
    $this->fields["6"] = "PBString";
    $this->values["6"] = "";
    $this->names_type["7"] = "endTime";
    $this->fields["7"] = "PBString";
    $this->values["7"] = "";
    $this->names_type["8"] = "title";
    $this->fields["8"] = "PBString";
    $this->values["8"] = "";
    $this->names_type["9"] = "onlineStatus";
    $this->fields["9"] = "PBInt";
    $this->values["9"] = "";
    $this->names_type["10"] = "settleStatus";
    $this->fields["10"] = "PBInt";
    $this->values["10"] = "";
    $this->names_type["11"] = "timeSetting";
    $this->fields["11"] = "PBString";
    $this->values["11"] = "";
    $this->names_type["12"] = "dmc";
    $this->fields["12"] = "PBString";
    $this->values["12"] = "";
    $this->names_type["13"] = "smooth";
    $this->fields["13"] = "PBString";
    $this->values["13"] = "";
    $this->names_type["14"] = "mobileDiscount";
    $this->fields["14"] = "PBInt";
    $this->values["14"] = "";
    $this->names_type["15"] = "outsideDiscount";
    $this->fields["15"] = "PBInt";
    $this->values["15"] = "";
    $this->names_type["16"] = "adChannels";
    $this->fields["16"] = "CampaignTag";
    $this->values["16"] = array();
    $this->names_type["17"] = "adAreas";
    $this->fields["17"] = "CampaignTag";
    $this->values["17"] = array();
    $this->names_type["18"] = "properties";
    $this->fields["18"] = "Pair";
    $this->values["18"] = array();
    $this->names_type["1001"] = "update_time";
    $this->fields["1001"] = "PBInt";
    $this->values["1001"] = "";
    $this->names_type["1002"] = "message_id";
    $this->fields["1002"] = "PBInt";
    $this->values["1002"] = "";
    $this->names_type["1003"] = "message_type";
    $this->fields["1003"] = "MessageType";
    $this->values["1003"] = "";
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function custId()
  {
    return $this->_get_value("2");
  }
  function set_custId($value)
  {
    return $this->_set_value("2", $value);
  }
  function memberId()
  {
    return $this->_get_value("3");
  }
  function set_memberId($value)
  {
    return $this->_set_value("3", $value);
  }
  function productLineId()
  {
    return $this->_get_value("4");
  }
  function set_productLineId($value)
  {
    return $this->_set_value("4", $value);
  }
  function type()
  {
    return $this->_get_value("5");
  }
  function set_type($value)
  {
    return $this->_set_value("5", $value);
  }
  function startTime()
  {
    return $this->_get_value("6");
  }
  function set_startTime($value)
  {
    return $this->_set_value("6", $value);
  }
  function endTime()
  {
    return $this->_get_value("7");
  }
  function set_endTime($value)
  {
    return $this->_set_value("7", $value);
  }
  function title()
  {
    return $this->_get_value("8");
  }
  function set_title($value)
  {
    return $this->_set_value("8", $value);
  }
  function onlineStatus()
  {
    return $this->_get_value("9");
  }
  function set_onlineStatus($value)
  {
    return $this->_set_value("9", $value);
  }
  function settleStatus()
  {
    return $this->_get_value("10");
  }
  function set_settleStatus($value)
  {
    return $this->_set_value("10", $value);
  }
  function timeSetting()
  {
    return $this->_get_value("11");
  }
  function set_timeSetting($value)
  {
    return $this->_set_value("11", $value);
  }
  function dmc()
  {
    return $this->_get_value("12");
  }
  function set_dmc($value)
  {
    return $this->_set_value("12", $value);
  }
  function smooth()
  {
    return $this->_get_value("13");
  }
  function set_smooth($value)
  {
    return $this->_set_value("13", $value);
  }
  function mobileDiscount()
  {
    return $this->_get_value("14");
  }
  function set_mobileDiscount($value)
  {
    return $this->_set_value("14", $value);
  }
  function outsideDiscount()
  {
    return $this->_get_value("15");
  }
  function set_outsideDiscount($value)
  {
    return $this->_set_value("15", $value);
  }
  function adChannels($offset)
  {
    return $this->_get_arr_value("16", $offset);
  }
  function add_adChannels()
  {
    return $this->_add_arr_value("16");
  }
  function set_adChannels($index, $value)
  {
    $this->_set_arr_value("16", $index, $value);
  }
  function remove_last_adChannels()
  {
    $this->_remove_last_arr_value("16");
  }
  function adChannels_size()
  {
    return $this->_get_arr_size("16");
  }
  function adAreas($offset)
  {
    return $this->_get_arr_value("17", $offset);
  }
  function add_adAreas()
  {
    return $this->_add_arr_value("17");
  }
  function set_adAreas($index, $value)
  {
    $this->_set_arr_value("17", $index, $value);
  }
  function remove_last_adAreas()
  {
    $this->_remove_last_arr_value("17");
  }
  function adAreas_size()
  {
    return $this->_get_arr_size("17");
  }
  function properties($offset)
  {
    return $this->_get_arr_value("18", $offset);
  }
  function add_properties()
  {
    return $this->_add_arr_value("18");
  }
  function set_properties($index, $value)
  {
    $this->_set_arr_value("18", $index, $value);
  }
  function remove_last_properties()
  {
    $this->_remove_last_arr_value("18");
  }
  function properties_size()
  {
    return $this->_get_arr_size("18");
  }
  function update_time()
  {
    return $this->_get_value("1001");
  }
  function set_update_time($value)
  {
    return $this->_set_value("1001", $value);
  }
  function message_id()
  {
    return $this->_get_value("1002");
  }
  function set_message_id($value)
  {
    return $this->_set_value("1002", $value);
  }
  function message_type()
  {
    return $this->_get_value("1003");
  }
  function set_message_type($value)
  {
    return $this->_set_value("1003", $value);
  }
}
class SimbaAdgroup extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "custId";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "memberId";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->names_type["4"] = "productId";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->names_type["5"] = "campaignId";
    $this->fields["5"] = "PBInt";
    $this->values["5"] = "";
    $this->names_type["6"] = "gmtCreate";
    $this->fields["6"] = "PBString";
    $this->values["6"] = "";
    $this->names_type["7"] = "gmtModified";
    $this->fields["7"] = "PBString";
    $this->values["7"] = "";
    $this->names_type["8"] = "title";
    $this->fields["8"] = "PBString";
    $this->values["8"] = "";
    $this->names_type["9"] = "bidPrice";
    $this->fields["9"] = "PBInt";
    $this->values["9"] = "";
    $this->names_type["10"] = "onlineStatus";
    $this->fields["10"] = "PBInt";
    $this->values["10"] = "";
    $this->names_type["11"] = "auditStatus";
    $this->fields["11"] = "PBInt";
    $this->values["11"] = "";
    $this->names_type["12"] = "auditTime";
    $this->fields["12"] = "PBString";
    $this->values["12"] = "";
    $this->names_type["13"] = "auditReason";
    $this->fields["13"] = "PBString";
    $this->values["13"] = "";
    $this->names_type["14"] = "properties";
    $this->fields["14"] = "Pair";
    $this->values["14"] = array();
    $this->names_type["1001"] = "update_time";
    $this->fields["1001"] = "PBInt";
    $this->values["1001"] = "";
    $this->names_type["1002"] = "message_id";
    $this->fields["1002"] = "PBInt";
    $this->values["1002"] = "";
    $this->names_type["1003"] = "message_type";
    $this->fields["1003"] = "MessageType";
    $this->values["1003"] = "";
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function custId()
  {
    return $this->_get_value("2");
  }
  function set_custId($value)
  {
    return $this->_set_value("2", $value);
  }
  function memberId()
  {
    return $this->_get_value("3");
  }
  function set_memberId($value)
  {
    return $this->_set_value("3", $value);
  }
  function productId()
  {
    return $this->_get_value("4");
  }
  function set_productId($value)
  {
    return $this->_set_value("4", $value);
  }
  function campaignId()
  {
    return $this->_get_value("5");
  }
  function set_campaignId($value)
  {
    return $this->_set_value("5", $value);
  }
  function gmtCreate()
  {
    return $this->_get_value("6");
  }
  function set_gmtCreate($value)
  {
    return $this->_set_value("6", $value);
  }
  function gmtModified()
  {
    return $this->_get_value("7");
  }
  function set_gmtModified($value)
  {
    return $this->_set_value("7", $value);
  }
  function title()
  {
    return $this->_get_value("8");
  }
  function set_title($value)
  {
    return $this->_set_value("8", $value);
  }
  function bidPrice()
  {
    return $this->_get_value("9");
  }
  function set_bidPrice($value)
  {
    return $this->_set_value("9", $value);
  }
  function onlineStatus()
  {
    return $this->_get_value("10");
  }
  function set_onlineStatus($value)
  {
    return $this->_set_value("10", $value);
  }
  function auditStatus()
  {
    return $this->_get_value("11");
  }
  function set_auditStatus($value)
  {
    return $this->_set_value("11", $value);
  }
  function auditTime()
  {
    return $this->_get_value("12");
  }
  function set_auditTime($value)
  {
    return $this->_set_value("12", $value);
  }
  function auditReason()
  {
    return $this->_get_value("13");
  }
  function set_auditReason($value)
  {
    return $this->_set_value("13", $value);
  }
  function properties($offset)
  {
    return $this->_get_arr_value("14", $offset);
  }
  function add_properties()
  {
    return $this->_add_arr_value("14");
  }
  function set_properties($index, $value)
  {
    $this->_set_arr_value("14", $index, $value);
  }
  function remove_last_properties()
  {
    $this->_remove_last_arr_value("14");
  }
  function properties_size()
  {
    return $this->_get_arr_size("14");
  }
  function update_time()
  {
    return $this->_get_value("1001");
  }
  function set_update_time($value)
  {
    return $this->_set_value("1001", $value);
  }
  function message_id()
  {
    return $this->_get_value("1002");
  }
  function set_message_id($value)
  {
    return $this->_set_value("1002", $value);
  }
  function message_type()
  {
    return $this->_get_value("1003");
  }
  function set_message_type($value)
  {
    return $this->_set_value("1003", $value);
  }
}
class SimbaCreative_CreativeElement extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "feedEntityTypeId";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "feedTypeId";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "feedId";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->names_type["4"] = "areaId";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->names_type["5"] = "fieldName";
    $this->fields["5"] = "PBString";
    $this->values["5"] = "";
    $this->names_type["6"] = "value";
    $this->fields["6"] = "PBString";
    $this->values["6"] = "";
  }
  function feedEntityTypeId()
  {
    return $this->_get_value("1");
  }
  function set_feedEntityTypeId($value)
  {
    return $this->_set_value("1", $value);
  }
  function feedTypeId()
  {
    return $this->_get_value("2");
  }
  function set_feedTypeId($value)
  {
    return $this->_set_value("2", $value);
  }
  function feedId()
  {
    return $this->_get_value("3");
  }
  function set_feedId($value)
  {
    return $this->_set_value("3", $value);
  }
  function areaId()
  {
    return $this->_get_value("4");
  }
  function set_areaId($value)
  {
    return $this->_set_value("4", $value);
  }
  function fieldName()
  {
    return $this->_get_value("5");
  }
  function set_fieldName($value)
  {
    return $this->_set_value("5", $value);
  }
  function value()
  {
    return $this->_get_value("6");
  }
  function set_value($value)
  {
    return $this->_set_value("6", $value);
  }
}
class SimbaCreative extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "custId";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "memberId";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->names_type["4"] = "productId";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->names_type["5"] = "templateId";
    $this->fields["5"] = "PBInt";
    $this->values["5"] = "";
    $this->names_type["6"] = "name";
    $this->fields["6"] = "PBString";
    $this->values["6"] = "";
    $this->names_type["7"] = "onlineStatus";
    $this->fields["7"] = "PBInt";
    $this->values["7"] = "";
    $this->names_type["8"] = "auditStatus";
    $this->fields["8"] = "PBInt";
    $this->values["8"] = "";
    $this->names_type["9"] = "feedStatus";
    $this->fields["9"] = "PBInt";
    $this->values["9"] = "";
    $this->names_type["10"] = "properties";
    $this->fields["10"] = "Pair";
    $this->values["10"] = array();
    $this->names_type["11"] = "qualityFlag";
    $this->fields["11"] = "PBInt";
    $this->values["11"] = "";
    $this->names_type["12"] = "elements";
    $this->fields["12"] = "SimbaCreative_CreativeElement";
    $this->values["12"] = array();
    $this->names_type["13"] = "gmtModified";
    $this->fields["13"] = "PBString";
    $this->values["13"] = "";
    $this->names_type["1001"] = "update_time";
    $this->fields["1001"] = "PBInt";
    $this->values["1001"] = "";
    $this->names_type["1002"] = "message_id";
    $this->fields["1002"] = "PBInt";
    $this->values["1002"] = "";
    $this->names_type["1003"] = "message_type";
    $this->fields["1003"] = "MessageType";
    $this->values["1003"] = "";
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function custId()
  {
    return $this->_get_value("2");
  }
  function set_custId($value)
  {
    return $this->_set_value("2", $value);
  }
  function memberId()
  {
    return $this->_get_value("3");
  }
  function set_memberId($value)
  {
    return $this->_set_value("3", $value);
  }
  function productId()
  {
    return $this->_get_value("4");
  }
  function set_productId($value)
  {
    return $this->_set_value("4", $value);
  }
  function templateId()
  {
    return $this->_get_value("5");
  }
  function set_templateId($value)
  {
    return $this->_set_value("5", $value);
  }
  function name()
  {
    return $this->_get_value("6");
  }
  function set_name($value)
  {
    return $this->_set_value("6", $value);
  }
  function onlineStatus()
  {
    return $this->_get_value("7");
  }
  function set_onlineStatus($value)
  {
    return $this->_set_value("7", $value);
  }
  function auditStatus()
  {
    return $this->_get_value("8");
  }
  function set_auditStatus($value)
  {
    return $this->_set_value("8", $value);
  }
  function feedStatus()
  {
    return $this->_get_value("9");
  }
  function set_feedStatus($value)
  {
    return $this->_set_value("9", $value);
  }
  function properties($offset)
  {
    return $this->_get_arr_value("10", $offset);
  }
  function add_properties()
  {
    return $this->_add_arr_value("10");
  }
  function set_properties($index, $value)
  {
    $this->_set_arr_value("10", $index, $value);
  }
  function remove_last_properties()
  {
    $this->_remove_last_arr_value("10");
  }
  function properties_size()
  {
    return $this->_get_arr_size("10");
  }
  function qualityFlag()
  {
    return $this->_get_value("11");
  }
  function set_qualityFlag($value)
  {
    return $this->_set_value("11", $value);
  }
  function elements($offset)
  {
    return $this->_get_arr_value("12", $offset);
  }
  function add_elements()
  {
    return $this->_add_arr_value("12");
  }
  function set_elements($index, $value)
  {
    $this->_set_arr_value("12", $index, $value);
  }
  function remove_last_elements()
  {
    $this->_remove_last_arr_value("12");
  }
  function elements_size()
  {
    return $this->_get_arr_size("12");
  }
  function gmtModified()
  {
    return $this->_get_value("13");
  }
  function set_gmtModified($value)
  {
    return $this->_set_value("13", $value);
  }
  function update_time()
  {
    return $this->_get_value("1001");
  }
  function set_update_time($value)
  {
    return $this->_set_value("1001", $value);
  }
  function message_id()
  {
    return $this->_get_value("1002");
  }
  function set_message_id($value)
  {
    return $this->_set_value("1002", $value);
  }
  function message_type()
  {
    return $this->_get_value("1003");
  }
  function set_message_type($value)
  {
    return $this->_set_value("1003", $value);
  }
}
class SimbaFeed extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "custId";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "memberId";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->names_type["4"] = "productId";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->names_type["5"] = "entityTypeId";
    $this->fields["5"] = "PBInt";
    $this->values["5"] = "";
    $this->names_type["8"] = "onlineStatus";
    $this->fields["8"] = "PBInt";
    $this->values["8"] = "";
    $this->names_type["9"] = "auditStatus";
    $this->fields["9"] = "PBInt";
    $this->values["9"] = "";
    $this->names_type["11"] = "outEntityId";
    $this->fields["11"] = "PBInt";
    $this->values["11"] = "";
    $this->names_type["12"] = "userId";
    $this->fields["12"] = "PBInt";
    $this->values["12"] = "";
    $this->names_type["13"] = "title";
    $this->fields["13"] = "PBString";
    $this->values["13"] = "";
    $this->names_type["14"] = "catId";
    $this->fields["14"] = "PBString";
    $this->values["14"] = "";
    $this->names_type["15"] = "imgUrl";
    $this->fields["15"] = "PBString";
    $this->values["15"] = "";
    $this->names_type["16"] = "linkUrl";
    $this->fields["16"] = "PBString";
    $this->values["16"] = "";
    $this->names_type["17"] = "custom";
    $this->fields["17"] = "PBString";
    $this->values["17"] = "";
    $this->names_type["18"] = "feedTypeId";
    $this->fields["18"] = "PBInt";
    $this->values["18"] = "";
    $this->names_type["19"] = "ext";
    $this->fields["19"] = "PBString";
    $this->values["19"] = "";
    $this->names_type["20"] = "properties";
    $this->fields["20"] = "Pair";
    $this->values["20"] = array();
    $this->names_type["1001"] = "update_time";
    $this->fields["1001"] = "PBInt";
    $this->values["1001"] = "";
    $this->names_type["1002"] = "message_id";
    $this->fields["1002"] = "PBInt";
    $this->values["1002"] = "";
    $this->names_type["1003"] = "message_type";
    $this->fields["1003"] = "MessageType";
    $this->values["1003"] = "";
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function custId()
  {
    return $this->_get_value("2");
  }
  function set_custId($value)
  {
    return $this->_set_value("2", $value);
  }
  function memberId()
  {
    return $this->_get_value("3");
  }
  function set_memberId($value)
  {
    return $this->_set_value("3", $value);
  }
  function productId()
  {
    return $this->_get_value("4");
  }
  function set_productId($value)
  {
    return $this->_set_value("4", $value);
  }
  function entityTypeId()
  {
    return $this->_get_value("5");
  }
  function set_entityTypeId($value)
  {
    return $this->_set_value("5", $value);
  }
  function onlineStatus()
  {
    return $this->_get_value("8");
  }
  function set_onlineStatus($value)
  {
    return $this->_set_value("8", $value);
  }
  function auditStatus()
  {
    return $this->_get_value("9");
  }
  function set_auditStatus($value)
  {
    return $this->_set_value("9", $value);
  }
  function outEntityId()
  {
    return $this->_get_value("11");
  }
  function set_outEntityId($value)
  {
    return $this->_set_value("11", $value);
  }
  function userId()
  {
    return $this->_get_value("12");
  }
  function set_userId($value)
  {
    return $this->_set_value("12", $value);
  }
  function title()
  {
    return $this->_get_value("13");
  }
  function set_title($value)
  {
    return $this->_set_value("13", $value);
  }
  function catId()
  {
    return $this->_get_value("14");
  }
  function set_catId($value)
  {
    return $this->_set_value("14", $value);
  }
  function imgUrl()
  {
    return $this->_get_value("15");
  }
  function set_imgUrl($value)
  {
    return $this->_set_value("15", $value);
  }
  function linkUrl()
  {
    return $this->_get_value("16");
  }
  function set_linkUrl($value)
  {
    return $this->_set_value("16", $value);
  }
  function custom()
  {
    return $this->_get_value("17");
  }
  function set_custom($value)
  {
    return $this->_set_value("17", $value);
  }
  function feedTypeId()
  {
    return $this->_get_value("18");
  }
  function set_feedTypeId($value)
  {
    return $this->_set_value("18", $value);
  }
  function ext()
  {
    return $this->_get_value("19");
  }
  function set_ext($value)
  {
    return $this->_set_value("19", $value);
  }
  function properties($offset)
  {
    return $this->_get_arr_value("20", $offset);
  }
  function add_properties()
  {
    return $this->_add_arr_value("20");
  }
  function set_properties($index, $value)
  {
    $this->_set_arr_value("20", $index, $value);
  }
  function remove_last_properties()
  {
    $this->_remove_last_arr_value("20");
  }
  function properties_size()
  {
    return $this->_get_arr_size("20");
  }
  function update_time()
  {
    return $this->_get_value("1001");
  }
  function set_update_time($value)
  {
    return $this->_set_value("1001", $value);
  }
  function message_id()
  {
    return $this->_get_value("1002");
  }
  function set_message_id($value)
  {
    return $this->_set_value("1002", $value);
  }
  function message_type()
  {
    return $this->_get_value("1003");
  }
  function set_message_type($value)
  {
    return $this->_set_value("1003", $value);
  }
}
class SimbaBidword extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "custId";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "memberId";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->names_type["4"] = "productId";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->names_type["5"] = "campaignId";
    $this->fields["5"] = "PBInt";
    $this->values["5"] = "";
    $this->names_type["6"] = "adgroupId";
    $this->fields["6"] = "PBInt";
    $this->values["6"] = "";
    $this->names_type["7"] = "onlineStatus";
    $this->fields["7"] = "PBInt";
    $this->values["7"] = "";
    $this->names_type["8"] = "wordType";
    $this->fields["8"] = "PBInt";
    $this->values["8"] = "";
    $this->names_type["9"] = "garbageStatus";
    $this->fields["9"] = "PBInt";
    $this->values["9"] = "";
    $this->names_type["10"] = "isDefaultPrice";
    $this->fields["10"] = "PBInt";
    $this->values["10"] = "";
    $this->names_type["11"] = "bidPrice";
    $this->fields["11"] = "PBInt";
    $this->values["11"] = "";
    $this->names_type["12"] = "auditStatus";
    $this->fields["12"] = "PBInt";
    $this->values["12"] = "";
    $this->names_type["13"] = "matchScope";
    $this->fields["13"] = "PBInt";
    $this->values["13"] = "";
    $this->names_type["14"] = "originalWord";
    $this->fields["14"] = "PBString";
    $this->values["14"] = "";
    $this->names_type["15"] = "properties";
    $this->fields["15"] = "Pair";
    $this->values["15"] = array();
    $this->names_type["1001"] = "update_time";
    $this->fields["1001"] = "PBInt";
    $this->values["1001"] = "";
    $this->names_type["1002"] = "message_id";
    $this->fields["1002"] = "PBInt";
    $this->values["1002"] = "";
    $this->names_type["1003"] = "message_type";
    $this->fields["1003"] = "MessageType";
    $this->values["1003"] = "";
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function custId()
  {
    return $this->_get_value("2");
  }
  function set_custId($value)
  {
    return $this->_set_value("2", $value);
  }
  function memberId()
  {
    return $this->_get_value("3");
  }
  function set_memberId($value)
  {
    return $this->_set_value("3", $value);
  }
  function productId()
  {
    return $this->_get_value("4");
  }
  function set_productId($value)
  {
    return $this->_set_value("4", $value);
  }
  function campaignId()
  {
    return $this->_get_value("5");
  }
  function set_campaignId($value)
  {
    return $this->_set_value("5", $value);
  }
  function adgroupId()
  {
    return $this->_get_value("6");
  }
  function set_adgroupId($value)
  {
    return $this->_set_value("6", $value);
  }
  function onlineStatus()
  {
    return $this->_get_value("7");
  }
  function set_onlineStatus($value)
  {
    return $this->_set_value("7", $value);
  }
  function wordType()
  {
    return $this->_get_value("8");
  }
  function set_wordType($value)
  {
    return $this->_set_value("8", $value);
  }
  function garbageStatus()
  {
    return $this->_get_value("9");
  }
  function set_garbageStatus($value)
  {
    return $this->_set_value("9", $value);
  }
  function isDefaultPrice()
  {
    return $this->_get_value("10");
  }
  function set_isDefaultPrice($value)
  {
    return $this->_set_value("10", $value);
  }
  function bidPrice()
  {
    return $this->_get_value("11");
  }
  function set_bidPrice($value)
  {
    return $this->_set_value("11", $value);
  }
  function auditStatus()
  {
    return $this->_get_value("12");
  }
  function set_auditStatus($value)
  {
    return $this->_set_value("12", $value);
  }
  function matchScope()
  {
    return $this->_get_value("13");
  }
  function set_matchScope($value)
  {
    return $this->_set_value("13", $value);
  }
  function originalWord()
  {
    return $this->_get_value("14");
  }
  function set_originalWord($value)
  {
    return $this->_set_value("14", $value);
  }
  function properties($offset)
  {
    return $this->_get_arr_value("15", $offset);
  }
  function add_properties()
  {
    return $this->_add_arr_value("15");
  }
  function set_properties($index, $value)
  {
    $this->_set_arr_value("15", $index, $value);
  }
  function remove_last_properties()
  {
    $this->_remove_last_arr_value("15");
  }
  function properties_size()
  {
    return $this->_get_arr_size("15");
  }
  function update_time()
  {
    return $this->_get_value("1001");
  }
  function set_update_time($value)
  {
    return $this->_set_value("1001", $value);
  }
  function message_id()
  {
    return $this->_get_value("1002");
  }
  function set_message_id($value)
  {
    return $this->_set_value("1002", $value);
  }
  function message_type()
  {
    return $this->_get_value("1003");
  }
  function set_message_type($value)
  {
    return $this->_set_value("1003", $value);
  }
}
class SimbaAdgroupTag extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "custId";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "memberId";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->names_type["4"] = "productId";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->names_type["5"] = "campaignId";
    $this->fields["5"] = "PBInt";
    $this->values["5"] = "";
    $this->names_type["6"] = "adgroupId";
    $this->fields["6"] = "PBInt";
    $this->values["6"] = "";
    $this->names_type["9"] = "onlineStatus";
    $this->fields["9"] = "PBInt";
    $this->values["9"] = "";
    $this->names_type["10"] = "tagType";
    $this->fields["10"] = "PBInt";
    $this->values["10"] = "";
    $this->names_type["11"] = "tagValue";
    $this->fields["11"] = "PBString";
    $this->values["11"] = "";
    $this->names_type["12"] = "priceModule";
    $this->fields["12"] = "PriceStatus";
    $this->values["12"] = "";
    $this->names_type["13"] = "bidPrice";
    $this->fields["13"] = "PBInt";
    $this->values["13"] = "";
    $this->names_type["14"] = "discount";
    $this->fields["14"] = "PBInt";
    $this->values["14"] = "";
    $this->names_type["15"] = "properties";
    $this->fields["15"] = "Pair";
    $this->values["15"] = array();
    $this->names_type["16"] = "targetingDimension";
    $this->fields["16"] = "PBInt";
    $this->values["16"] = "";
    $this->names_type["17"] = "isDefaultPrice";
    $this->fields["17"] = "PBInt";
    $this->values["17"] = "";
    $this->names_type["18"] = "crowdOnlineStatus";
    $this->fields["18"] = "PBInt";
    $this->values["18"] = "";
    $this->names_type["19"] = "crowdType";
    $this->fields["19"] = "PBInt";
    $this->values["19"] = "";
    $this->names_type["20"] = "dmpCrowdId";
    $this->fields["20"] = "PBInt";
    $this->values["20"] = "";
    $this->names_type["21"] = "filterCrowdId";
    $this->fields["21"] = "PBInt";
    $this->values["21"] = "";
    $this->names_type["22"] = "subCrowdId";
    $this->fields["22"] = "PBInt";
    $this->values["22"] = array();
    $this->names_type["1001"] = "update_time";
    $this->fields["1001"] = "PBInt";
    $this->values["1001"] = "";
    $this->names_type["1002"] = "message_id";
    $this->fields["1002"] = "PBInt";
    $this->values["1002"] = "";
    $this->names_type["1003"] = "message_type";
    $this->fields["1003"] = "MessageType";
    $this->values["1003"] = "";
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function custId()
  {
    return $this->_get_value("2");
  }
  function set_custId($value)
  {
    return $this->_set_value("2", $value);
  }
  function memberId()
  {
    return $this->_get_value("3");
  }
  function set_memberId($value)
  {
    return $this->_set_value("3", $value);
  }
  function productId()
  {
    return $this->_get_value("4");
  }
  function set_productId($value)
  {
    return $this->_set_value("4", $value);
  }
  function campaignId()
  {
    return $this->_get_value("5");
  }
  function set_campaignId($value)
  {
    return $this->_set_value("5", $value);
  }
  function adgroupId()
  {
    return $this->_get_value("6");
  }
  function set_adgroupId($value)
  {
    return $this->_set_value("6", $value);
  }
  function onlineStatus()
  {
    return $this->_get_value("9");
  }
  function set_onlineStatus($value)
  {
    return $this->_set_value("9", $value);
  }
  function tagType()
  {
    return $this->_get_value("10");
  }
  function set_tagType($value)
  {
    return $this->_set_value("10", $value);
  }
  function tagValue()
  {
    return $this->_get_value("11");
  }
  function set_tagValue($value)
  {
    return $this->_set_value("11", $value);
  }
  function priceModule()
  {
    return $this->_get_value("12");
  }
  function set_priceModule($value)
  {
    return $this->_set_value("12", $value);
  }
  function bidPrice()
  {
    return $this->_get_value("13");
  }
  function set_bidPrice($value)
  {
    return $this->_set_value("13", $value);
  }
  function discount()
  {
    return $this->_get_value("14");
  }
  function set_discount($value)
  {
    return $this->_set_value("14", $value);
  }
  function properties($offset)
  {
    return $this->_get_arr_value("15", $offset);
  }
  function add_properties()
  {
    return $this->_add_arr_value("15");
  }
  function set_properties($index, $value)
  {
    $this->_set_arr_value("15", $index, $value);
  }
  function remove_last_properties()
  {
    $this->_remove_last_arr_value("15");
  }
  function properties_size()
  {
    return $this->_get_arr_size("15");
  }
  function targetingDimension()
  {
    return $this->_get_value("16");
  }
  function set_targetingDimension($value)
  {
    return $this->_set_value("16", $value);
  }
  function isDefaultPrice()
  {
    return $this->_get_value("17");
  }
  function set_isDefaultPrice($value)
  {
    return $this->_set_value("17", $value);
  }
  function crowdOnlineStatus()
  {
    return $this->_get_value("18");
  }
  function set_crowdOnlineStatus($value)
  {
    return $this->_set_value("18", $value);
  }
  function crowdType()
  {
    return $this->_get_value("19");
  }
  function set_crowdType($value)
  {
    return $this->_set_value("19", $value);
  }
  function dmpCrowdId()
  {
    return $this->_get_value("20");
  }
  function set_dmpCrowdId($value)
  {
    return $this->_set_value("20", $value);
  }
  function filterCrowdId()
  {
    return $this->_get_value("21");
  }
  function set_filterCrowdId($value)
  {
    return $this->_set_value("21", $value);
  }
  function subCrowdId($offset)
  {
    $v = $this->_get_arr_value("22", $offset);
    return $v->get_value();
  }
  function append_subCrowdId($value)
  {
    $v = $this->_add_arr_value("22");
    $v->set_value($value);
  }
  function set_subCrowdId($index, $value)
  {
    $v = new $this->fields["22"]();
    $v->set_value($value);
    $this->_set_arr_value("22", $index, $v);
  }
  function remove_last_subCrowdId()
  {
    $this->_remove_last_arr_value("22");
  }
  function subCrowdId_size()
  {
    return $this->_get_arr_size("22");
  }
  function update_time()
  {
    return $this->_get_value("1001");
  }
  function set_update_time($value)
  {
    return $this->_set_value("1001", $value);
  }
  function message_id()
  {
    return $this->_get_value("1002");
  }
  function set_message_id($value)
  {
    return $this->_set_value("1002", $value);
  }
  function message_type()
  {
    return $this->_get_value("1003");
  }
  function set_message_type($value)
  {
    return $this->_set_value("1003", $value);
  }
}
class SimbaFeedRef extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "custId";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "memberId";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->names_type["4"] = "productId";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->names_type["5"] = "campaignId";
    $this->fields["5"] = "PBInt";
    $this->values["5"] = "";
    $this->names_type["6"] = "adgroupId";
    $this->fields["6"] = "PBInt";
    $this->values["6"] = "";
    $this->names_type["7"] = "feedId";
    $this->fields["7"] = "PBInt";
    $this->values["7"] = "";
    $this->names_type["8"] = "entityTypeId";
    $this->fields["8"] = "PBInt";
    $this->values["8"] = "";
    $this->names_type["9"] = "feedTypeId";
    $this->fields["9"] = "PBInt";
    $this->values["9"] = "";
    $this->names_type["1001"] = "update_time";
    $this->fields["1001"] = "PBInt";
    $this->values["1001"] = "";
    $this->names_type["1002"] = "message_id";
    $this->fields["1002"] = "PBInt";
    $this->values["1002"] = "";
    $this->names_type["1003"] = "message_type";
    $this->fields["1003"] = "MessageType";
    $this->values["1003"] = "";
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function custId()
  {
    return $this->_get_value("2");
  }
  function set_custId($value)
  {
    return $this->_set_value("2", $value);
  }
  function memberId()
  {
    return $this->_get_value("3");
  }
  function set_memberId($value)
  {
    return $this->_set_value("3", $value);
  }
  function productId()
  {
    return $this->_get_value("4");
  }
  function set_productId($value)
  {
    return $this->_set_value("4", $value);
  }
  function campaignId()
  {
    return $this->_get_value("5");
  }
  function set_campaignId($value)
  {
    return $this->_set_value("5", $value);
  }
  function adgroupId()
  {
    return $this->_get_value("6");
  }
  function set_adgroupId($value)
  {
    return $this->_set_value("6", $value);
  }
  function feedId()
  {
    return $this->_get_value("7");
  }
  function set_feedId($value)
  {
    return $this->_set_value("7", $value);
  }
  function entityTypeId()
  {
    return $this->_get_value("8");
  }
  function set_entityTypeId($value)
  {
    return $this->_set_value("8", $value);
  }
  function feedTypeId()
  {
    return $this->_get_value("9");
  }
  function set_feedTypeId($value)
  {
    return $this->_set_value("9", $value);
  }
  function update_time()
  {
    return $this->_get_value("1001");
  }
  function set_update_time($value)
  {
    return $this->_set_value("1001", $value);
  }
  function message_id()
  {
    return $this->_get_value("1002");
  }
  function set_message_id($value)
  {
    return $this->_set_value("1002", $value);
  }
  function message_type()
  {
    return $this->_get_value("1003");
  }
  function set_message_type($value)
  {
    return $this->_set_value("1003", $value);
  }
}
class SimbaCreativeRef extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "custId";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "memberId";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->names_type["4"] = "productId";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->names_type["5"] = "campaignId";
    $this->fields["5"] = "PBInt";
    $this->values["5"] = "";
    $this->names_type["6"] = "adgroupId";
    $this->fields["6"] = "PBInt";
    $this->values["6"] = "";
    $this->names_type["7"] = "creativeId";
    $this->fields["7"] = "PBInt";
    $this->values["7"] = "";
    $this->names_type["8"] = "auditStatus";
    $this->fields["8"] = "PBInt";
    $this->values["8"] = "";
    $this->names_type["1001"] = "update_time";
    $this->fields["1001"] = "PBInt";
    $this->values["1001"] = "";
    $this->names_type["1002"] = "message_id";
    $this->fields["1002"] = "PBInt";
    $this->values["1002"] = "";
    $this->names_type["1003"] = "message_type";
    $this->fields["1003"] = "MessageType";
    $this->values["1003"] = "";
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function custId()
  {
    return $this->_get_value("2");
  }
  function set_custId($value)
  {
    return $this->_set_value("2", $value);
  }
  function memberId()
  {
    return $this->_get_value("3");
  }
  function set_memberId($value)
  {
    return $this->_set_value("3", $value);
  }
  function productId()
  {
    return $this->_get_value("4");
  }
  function set_productId($value)
  {
    return $this->_set_value("4", $value);
  }
  function campaignId()
  {
    return $this->_get_value("5");
  }
  function set_campaignId($value)
  {
    return $this->_set_value("5", $value);
  }
  function adgroupId()
  {
    return $this->_get_value("6");
  }
  function set_adgroupId($value)
  {
    return $this->_set_value("6", $value);
  }
  function creativeId()
  {
    return $this->_get_value("7");
  }
  function set_creativeId($value)
  {
    return $this->_set_value("7", $value);
  }
  function auditStatus()
  {
    return $this->_get_value("8");
  }
  function set_auditStatus($value)
  {
    return $this->_set_value("8", $value);
  }
  function update_time()
  {
    return $this->_get_value("1001");
  }
  function set_update_time($value)
  {
    return $this->_set_value("1001", $value);
  }
  function message_id()
  {
    return $this->_get_value("1002");
  }
  function set_message_id($value)
  {
    return $this->_set_value("1002", $value);
  }
  function message_type()
  {
    return $this->_get_value("1003");
  }
  function set_message_type($value)
  {
    return $this->_set_value("1003", $value);
  }
}
?>