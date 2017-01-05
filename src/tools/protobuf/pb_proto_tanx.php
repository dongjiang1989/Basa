<?php
require_once('/home/dongjiang.dongj/Source/basa/src/tools/protobuf/parser/../message/pb_message.php');
class MobileCreative_Creative_Attr extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "name";
    $this->fields["1"] = "PBString";
    $this->values["1"] = "";
    $this->names_type["2"] = "value";
    $this->fields["2"] = "PBString";
    $this->values["2"] = "";
  }
  function name()
  {
    return $this->_get_value("1");
  }
  function set_name($value)
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
class MobileCreative_Creative_TrackingEvents extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "impression_event";
    $this->fields["1"] = "PBString";
    $this->values["1"] = array();
    $this->names_type["2"] = "click_event";
    $this->fields["2"] = "PBString";
    $this->values["2"] = array();
    $this->names_type["3"] = "download_complete_event";
    $this->fields["3"] = "PBString";
    $this->values["3"] = array();
  }
  function impression_event($offset)
  {
    $v = $this->_get_arr_value("1", $offset);
    return $v->get_value();
  }
  function append_impression_event($value)
  {
    $v = $this->_add_arr_value("1");
    $v->set_value($value);
  }
  function set_impression_event($index, $value)
  {
    $v = new $this->fields["1"]();
    $v->set_value($value);
    $this->_set_arr_value("1", $index, $v);
  }
  function remove_last_impression_event()
  {
    $this->_remove_last_arr_value("1");
  }
  function impression_event_size()
  {
    return $this->_get_arr_size("1");
  }
  function click_event($offset)
  {
    $v = $this->_get_arr_value("2", $offset);
    return $v->get_value();
  }
  function append_click_event($value)
  {
    $v = $this->_add_arr_value("2");
    $v->set_value($value);
  }
  function set_click_event($index, $value)
  {
    $v = new $this->fields["2"]();
    $v->set_value($value);
    $this->_set_arr_value("2", $index, $v);
  }
  function remove_last_click_event()
  {
    $this->_remove_last_arr_value("2");
  }
  function click_event_size()
  {
    return $this->_get_arr_size("2");
  }
  function download_complete_event($offset)
  {
    $v = $this->_get_arr_value("3", $offset);
    return $v->get_value();
  }
  function append_download_complete_event($value)
  {
    $v = $this->_add_arr_value("3");
    $v->set_value($value);
  }
  function set_download_complete_event($index, $value)
  {
    $v = new $this->fields["3"]();
    $v->set_value($value);
    $this->_set_arr_value("3", $index, $v);
  }
  function remove_last_download_complete_event()
  {
    $this->_remove_last_arr_value("3");
  }
  function download_complete_event_size()
  {
    return $this->_get_arr_size("3");
  }
}
class MobileCreative_Creative extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "img_url";
    $this->fields["1"] = "PBString";
    $this->values["1"] = "";
    $this->names_type["2"] = "img_size";
    $this->fields["2"] = "PBString";
    $this->values["2"] = "";
    $this->names_type["3"] = "title";
    $this->fields["3"] = "PBString";
    $this->values["3"] = "";
    $this->names_type["4"] = "click_url";
    $this->fields["4"] = "PBString";
    $this->values["4"] = "";
    $this->names_type["5"] = "destination_url";
    $this->fields["5"] = "PBString";
    $this->values["5"] = "";
    $this->names_type["6"] = "attr";
    $this->fields["6"] = "MobileCreative_Creative_Attr";
    $this->values["6"] = array();
    $this->names_type["7"] = "creative_id";
    $this->fields["7"] = "PBString";
    $this->values["7"] = "";
    $this->names_type["8"] = "category";
    $this->fields["8"] = "PBInt";
    $this->values["8"] = array();
    $this->names_type["9"] = "tracking_events";
    $this->fields["9"] = "MobileCreative_Creative_TrackingEvents";
    $this->values["9"] = "";
  }
  function img_url()
  {
    return $this->_get_value("1");
  }
  function set_img_url($value)
  {
    return $this->_set_value("1", $value);
  }
  function img_size()
  {
    return $this->_get_value("2");
  }
  function set_img_size($value)
  {
    return $this->_set_value("2", $value);
  }
  function title()
  {
    return $this->_get_value("3");
  }
  function set_title($value)
  {
    return $this->_set_value("3", $value);
  }
  function click_url()
  {
    return $this->_get_value("4");
  }
  function set_click_url($value)
  {
    return $this->_set_value("4", $value);
  }
  function destination_url()
  {
    return $this->_get_value("5");
  }
  function set_destination_url($value)
  {
    return $this->_set_value("5", $value);
  }
  function attr($offset)
  {
    return $this->_get_arr_value("6", $offset);
  }
  function add_attr()
  {
    return $this->_add_arr_value("6");
  }
  function set_attr($index, $value)
  {
    $this->_set_arr_value("6", $index, $value);
  }
  function remove_last_attr()
  {
    $this->_remove_last_arr_value("6");
  }
  function attr_size()
  {
    return $this->_get_arr_size("6");
  }
  function creative_id()
  {
    return $this->_get_value("7");
  }
  function set_creative_id($value)
  {
    return $this->_set_value("7", $value);
  }
  function category($offset)
  {
    $v = $this->_get_arr_value("8", $offset);
    return $v->get_value();
  }
  function append_category($value)
  {
    $v = $this->_add_arr_value("8");
    $v->set_value($value);
  }
  function set_category($index, $value)
  {
    $v = new $this->fields["8"]();
    $v->set_value($value);
    $this->_set_arr_value("8", $index, $v);
  }
  function remove_last_category()
  {
    $this->_remove_last_arr_value("8");
  }
  function category_size()
  {
    return $this->_get_arr_size("8");
  }
  function tracking_events($offset)
  {
    return $this->_get_arr_value("9", $offset);
  }
  function add_tracking_events()
  {
    return $this->_add_arr_value("9");
  }
  function set_tracking_events($index, $value)
  {
    $this->_set_arr_value("9", $index, $value);
  }
  function remove_last_tracking_events()
  {
    $this->_remove_last_arr_value("9");
  }
  function tracking_events_size()
  {
    return $this->_get_arr_size("9");
  }
}
class MobileCreative extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "version";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "bid";
    $this->fields["2"] = "PBString";
    $this->values["2"] = "";
    $this->names_type["3"] = "view_type";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->names_type["4"] = "creatives";
    $this->fields["4"] = "MobileCreative_Creative";
    $this->values["4"] = array();
    $this->names_type["5"] = "native_template_id";
    $this->fields["5"] = "PBString";
    $this->values["5"] = "";
  }
  function version()
  {
    return $this->_get_value("1");
  }
  function set_version($value)
  {
    return $this->_set_value("1", $value);
  }
  function bid()
  {
    return $this->_get_value("2");
  }
  function set_bid($value)
  {
    return $this->_set_value("2", $value);
  }
  function view_type()
  {
    return $this->_get_value("3");
  }
  function set_view_type($value)
  {
    return $this->_set_value("3", $value);
  }
  function creatives($offset)
  {
    return $this->_get_arr_value("4", $offset);
  }
  function add_creatives()
  {
    return $this->_add_arr_value("4");
  }
  function set_creatives($index, $value)
  {
    $this->_set_arr_value("4", $index, $value);
  }
  function remove_last_creatives()
  {
    $this->_remove_last_arr_value("4");
  }
  function creatives_size()
  {
    return $this->_get_arr_size("4");
  }
  function native_template_id()
  {
    return $this->_get_value("5");
  }
  function set_native_template_id($value)
  {
    return $this->_set_value("5", $value);
  }
}
class BidRequest_AdzInfo_Location extends PBEnum
{
  const NA  = 0;
  const FIRST_VIEW  = 1;
  const OTHER_VIEW  = 2;
}
class BidRequest_AdzInfo_ViewScreen extends PBEnum
{
  const SCREEN_NA  = 0;
  const SCREEN_FIRST  = 1;
  const SCREEN_SECOND  = 2;
  const SCREEN_THIRD  = 3;
  const SCREEN_FOURTH  = 4;
  const SCREEN_FIFTH  = 5;
  const SCREEN_OTHER  = 6;
}
class BidRequest_AdzInfo extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "pid";
    $this->fields["2"] = "PBString";
    $this->values["2"] = "";
    $this->names_type["11"] = "publisher_id";
    $this->fields["11"] = "PBString";
    $this->values["11"] = "";
    $this->names_type["3"] = "size";
    $this->fields["3"] = "PBString";
    $this->values["3"] = "";
    $this->names_type["4"] = "ad_bid_count";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->values["4"] = new PBInt();
    $this->values["4"]->value = 2;
    $this->names_type["5"] = "view_type";
    $this->fields["5"] = "PBInt";
    $this->values["5"] = array();
    $this->names_type["6"] = "excluded_filter";
    $this->fields["6"] = "PBInt";
    $this->values["6"] = array();
    $this->names_type["7"] = "min_cpm_price";
    $this->fields["7"] = "PBInt";
    $this->values["7"] = "";
    $this->names_type["8"] = "adz_location";
    $this->fields["8"] = "BidRequest_AdzInfo_Location";
    $this->values["8"] = "";
    $this->values["8"] = new BidRequest_AdzInfo_Location();
    $this->values["8"]->value = BidRequest_AdzInfo_Location::NA;
    $this->names_type["9"] = "view_screen";
    $this->fields["9"] = "BidRequest_AdzInfo_ViewScreen";
    $this->values["9"] = "";
    $this->values["9"] = new BidRequest_AdzInfo_ViewScreen();
    $this->values["9"]->value = BidRequest_AdzInfo_ViewScreen::SCREEN_NA;
    $this->names_type["10"] = "page_session_ad_idx";
    $this->fields["10"] = "PBInt";
    $this->values["10"] = "";
    $this->names_type["12"] = "api";
    $this->fields["12"] = "PBInt";
    $this->values["12"] = array();
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function pid()
  {
    return $this->_get_value("2");
  }
  function set_pid($value)
  {
    return $this->_set_value("2", $value);
  }
  function publisher_id()
  {
    return $this->_get_value("11");
  }
  function set_publisher_id($value)
  {
    return $this->_set_value("11", $value);
  }
  function size()
  {
    return $this->_get_value("3");
  }
  function set_size($value)
  {
    return $this->_set_value("3", $value);
  }
  function ad_bid_count()
  {
    return $this->_get_value("4");
  }
  function set_ad_bid_count($value)
  {
    return $this->_set_value("4", $value);
  }
  function view_type($offset)
  {
    $v = $this->_get_arr_value("5", $offset);
    return $v->get_value();
  }
  function append_view_type($value)
  {
    $v = $this->_add_arr_value("5");
    $v->set_value($value);
  }
  function set_view_type($index, $value)
  {
    $v = new $this->fields["5"]();
    $v->set_value($value);
    $this->_set_arr_value("5", $index, $v);
  }
  function remove_last_view_type()
  {
    $this->_remove_last_arr_value("5");
  }
  function view_type_size()
  {
    return $this->_get_arr_size("5");
  }
  function excluded_filter($offset)
  {
    $v = $this->_get_arr_value("6", $offset);
    return $v->get_value();
  }
  function append_excluded_filter($value)
  {
    $v = $this->_add_arr_value("6");
    $v->set_value($value);
  }
  function set_excluded_filter($index, $value)
  {
    $v = new $this->fields["6"]();
    $v->set_value($value);
    $this->_set_arr_value("6", $index, $v);
  }
  function remove_last_excluded_filter()
  {
    $this->_remove_last_arr_value("6");
  }
  function excluded_filter_size()
  {
    return $this->_get_arr_size("6");
  }
  function min_cpm_price()
  {
    return $this->_get_value("7");
  }
  function set_min_cpm_price($value)
  {
    return $this->_set_value("7", $value);
  }
  function adz_location()
  {
    return $this->_get_value("8");
  }
  function set_adz_location($value)
  {
    return $this->_set_value("8", $value);
  }
  function view_screen()
  {
    return $this->_get_value("9");
  }
  function set_view_screen($value)
  {
    return $this->_set_value("9", $value);
  }
  function page_session_ad_idx()
  {
    return $this->_get_value("10");
  }
  function set_page_session_ad_idx($value)
  {
    return $this->_set_value("10", $value);
  }
  function api($offset)
  {
    $v = $this->_get_arr_value("12", $offset);
    return $v->get_value();
  }
  function append_api($value)
  {
    $v = $this->_add_arr_value("12");
    $v->set_value($value);
  }
  function set_api($index, $value)
  {
    $v = new $this->fields["12"]();
    $v->set_value($value);
    $this->_set_arr_value("12", $index, $v);
  }
  function remove_last_api()
  {
    $this->_remove_last_arr_value("12");
  }
  function api_size()
  {
    return $this->_get_arr_size("12");
  }
}
class BidRequest_UserAttribute extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "timestamp";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function timestamp()
  {
    return $this->_get_value("2");
  }
  function set_timestamp($value)
  {
    return $this->_set_value("2", $value);
  }
}
class BidRequest_PrivateInfo extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "tanx_cnaui";
    $this->fields["1"] = "PBString";
    $this->values["1"] = "";
    $this->names_type["2"] = "risk_control";
    $this->fields["2"] = "PBString";
    $this->values["2"] = "";
  }
  function tanx_cnaui()
  {
    return $this->_get_value("1");
  }
  function set_tanx_cnaui($value)
  {
    return $this->_set_value("1", $value);
  }
  function risk_control()
  {
    return $this->_get_value("2");
  }
  function set_risk_control($value)
  {
    return $this->_set_value("2", $value);
  }
}
class BidRequest_Mobile_Device extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "platform";
    $this->fields["1"] = "PBString";
    $this->values["1"] = "";
    $this->names_type["2"] = "brand";
    $this->fields["2"] = "PBString";
    $this->values["2"] = "";
    $this->names_type["3"] = "model";
    $this->fields["3"] = "PBString";
    $this->values["3"] = "";
    $this->names_type["4"] = "os";
    $this->fields["4"] = "PBString";
    $this->values["4"] = "";
    $this->names_type["5"] = "os_version";
    $this->fields["5"] = "PBString";
    $this->values["5"] = "";
    $this->names_type["6"] = "network";
    $this->fields["6"] = "PBInt";
    $this->values["6"] = "";
    $this->names_type["7"] = "operator";
    $this->fields["7"] = "PBInt";
    $this->values["7"] = "";
    $this->names_type["8"] = "longitude";
    $this->fields["8"] = "PBString";
    $this->values["8"] = "";
    $this->names_type["9"] = "latitude";
    $this->fields["9"] = "PBString";
    $this->values["9"] = "";
    $this->names_type["10"] = "device_size";
    $this->fields["10"] = "PBString";
    $this->values["10"] = "";
    $this->names_type["11"] = "device_id";
    $this->fields["11"] = "PBString";
    $this->values["11"] = "";
    $this->names_type["12"] = "device_pixel_ratio";
    $this->fields["12"] = "PBInt";
    $this->values["12"] = "";
    $this->values["12"] = new PBInt();
    $this->values["12"]->value = 1000;
  }
  function platform()
  {
    return $this->_get_value("1");
  }
  function set_platform($value)
  {
    return $this->_set_value("1", $value);
  }
  function brand()
  {
    return $this->_get_value("2");
  }
  function set_brand($value)
  {
    return $this->_set_value("2", $value);
  }
  function model()
  {
    return $this->_get_value("3");
  }
  function set_model($value)
  {
    return $this->_set_value("3", $value);
  }
  function os()
  {
    return $this->_get_value("4");
  }
  function set_os($value)
  {
    return $this->_set_value("4", $value);
  }
  function os_version()
  {
    return $this->_get_value("5");
  }
  function set_os_version($value)
  {
    return $this->_set_value("5", $value);
  }
  function network()
  {
    return $this->_get_value("6");
  }
  function set_network($value)
  {
    return $this->_set_value("6", $value);
  }
  function operator()
  {
    return $this->_get_value("7");
  }
  function set_operator($value)
  {
    return $this->_set_value("7", $value);
  }
  function longitude()
  {
    return $this->_get_value("8");
  }
  function set_longitude($value)
  {
    return $this->_set_value("8", $value);
  }
  function latitude()
  {
    return $this->_get_value("9");
  }
  function set_latitude($value)
  {
    return $this->_set_value("9", $value);
  }
  function device_size()
  {
    return $this->_get_value("10");
  }
  function set_device_size($value)
  {
    return $this->_set_value("10", $value);
  }
  function device_id()
  {
    return $this->_get_value("11");
  }
  function set_device_id($value)
  {
    return $this->_set_value("11", $value);
  }
  function device_pixel_ratio()
  {
    return $this->_get_value("12");
  }
  function set_device_pixel_ratio($value)
  {
    return $this->_set_value("12", $value);
  }
}
class BidRequest_Mobile extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "is_app";
    $this->fields["1"] = "PBBool";
    $this->values["1"] = "";
    $this->names_type["2"] = "ad_num";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "ad_keyword";
    $this->fields["3"] = "PBString";
    $this->values["3"] = array();
    $this->names_type["4"] = "is_fullscreen";
    $this->fields["4"] = "PBBool";
    $this->values["4"] = "";
    $this->names_type["5"] = "package_name";
    $this->fields["5"] = "PBString";
    $this->values["5"] = "";
    $this->names_type["6"] = "device";
    $this->fields["6"] = "BidRequest_Mobile_Device";
    $this->values["6"] = "";
    $this->names_type["7"] = "native_template_id";
    $this->fields["7"] = "PBString";
    $this->values["7"] = array();
    $this->names_type["8"] = "landing_type";
    $this->fields["8"] = "PBInt";
    $this->values["8"] = array();
  }
  function is_app()
  {
    return $this->_get_value("1");
  }
  function set_is_app($value)
  {
    return $this->_set_value("1", $value);
  }
  function ad_num()
  {
    return $this->_get_value("2");
  }
  function set_ad_num($value)
  {
    return $this->_set_value("2", $value);
  }
  function ad_keyword($offset)
  {
    $v = $this->_get_arr_value("3", $offset);
    return $v->get_value();
  }
  function append_ad_keyword($value)
  {
    $v = $this->_add_arr_value("3");
    $v->set_value($value);
  }
  function set_ad_keyword($index, $value)
  {
    $v = new $this->fields["3"]();
    $v->set_value($value);
    $this->_set_arr_value("3", $index, $v);
  }
  function remove_last_ad_keyword()
  {
    $this->_remove_last_arr_value("3");
  }
  function ad_keyword_size()
  {
    return $this->_get_arr_size("3");
  }
  function is_fullscreen()
  {
    return $this->_get_value("4");
  }
  function set_is_fullscreen($value)
  {
    return $this->_set_value("4", $value);
  }
  function package_name()
  {
    return $this->_get_value("5");
  }
  function set_package_name($value)
  {
    return $this->_set_value("5", $value);
  }
  function device($offset)
  {
    return $this->_get_arr_value("6", $offset);
  }
  function add_device()
  {
    return $this->_add_arr_value("6");
  }
  function set_device($index, $value)
  {
    $this->_set_arr_value("6", $index, $value);
  }
  function remove_last_device()
  {
    $this->_remove_last_arr_value("6");
  }
  function device_size()
  {
    return $this->_get_arr_size("6");
  }
  function native_template_id($offset)
  {
    $v = $this->_get_arr_value("7", $offset);
    return $v->get_value();
  }
  function append_native_template_id($value)
  {
    $v = $this->_add_arr_value("7");
    $v->set_value($value);
  }
  function set_native_template_id($index, $value)
  {
    $v = new $this->fields["7"]();
    $v->set_value($value);
    $this->_set_arr_value("7", $index, $v);
  }
  function remove_last_native_template_id()
  {
    $this->_remove_last_arr_value("7");
  }
  function native_template_id_size()
  {
    return $this->_get_arr_size("7");
  }
  function landing_type($offset)
  {
    $v = $this->_get_arr_value("8", $offset);
    return $v->get_value();
  }
  function append_landing_type($value)
  {
    $v = $this->_add_arr_value("8");
    $v->set_value($value);
  }
  function set_landing_type($index, $value)
  {
    $v = new $this->fields["8"]();
    $v->set_value($value);
    $this->_set_arr_value("8", $index, $v);
  }
  function remove_last_landing_type()
  {
    $this->_remove_last_arr_value("8");
  }
  function landing_type_size()
  {
    return $this->_get_arr_size("8");
  }
}
class BidRequest_ContentCategory extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "confidence_level";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function confidence_level()
  {
    return $this->_get_value("2");
  }
  function set_confidence_level($value)
  {
    return $this->_set_value("2", $value);
  }
}
class BidRequest_Video_VideoFormat extends PBEnum
{
  const VIDEO_FLASH  = 0;
  const VIDEO_HTML5  = 1;
}
class BidRequest_Video_Content extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "title";
    $this->fields["1"] = "PBString";
    $this->values["1"] = "";
    $this->names_type["2"] = "duration";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "keywords";
    $this->fields["3"] = "PBString";
    $this->values["3"] = array();
  }
  function title()
  {
    return $this->_get_value("1");
  }
  function set_title($value)
  {
    return $this->_set_value("1", $value);
  }
  function duration()
  {
    return $this->_get_value("2");
  }
  function set_duration($value)
  {
    return $this->_set_value("2", $value);
  }
  function keywords($offset)
  {
    $v = $this->_get_arr_value("3", $offset);
    return $v->get_value();
  }
  function append_keywords($value)
  {
    $v = $this->_add_arr_value("3");
    $v->set_value($value);
  }
  function set_keywords($index, $value)
  {
    $v = new $this->fields["3"]();
    $v->set_value($value);
    $this->_set_arr_value("3", $index, $v);
  }
  function remove_last_keywords()
  {
    $this->_remove_last_arr_value("3");
  }
  function keywords_size()
  {
    return $this->_get_arr_size("3");
  }
}
class BidRequest_Video extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "video_format";
    $this->fields["1"] = "BidRequest_Video_VideoFormat";
    $this->values["1"] = array();
    $this->names_type["2"] = "content";
    $this->fields["2"] = "BidRequest_Video_Content";
    $this->values["2"] = "";
    $this->names_type["3"] = "videoad_start_delay";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->names_type["4"] = "videoad_section_start_delay";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->names_type["5"] = "min_ad_duration";
    $this->fields["5"] = "PBInt";
    $this->values["5"] = "";
    $this->names_type["6"] = "max_ad_duration";
    $this->fields["6"] = "PBInt";
    $this->values["6"] = "";
    $this->names_type["7"] = "protocol";
    $this->fields["7"] = "PBString";
    $this->values["7"] = "";
  }
  function video_format($offset)
  {
    $v = $this->_get_arr_value("1", $offset);
    return $v->get_value();
  }
  function append_video_format($value)
  {
    $v = $this->_add_arr_value("1");
    $v->set_value($value);
  }
  function set_video_format($index, $value)
  {
    $v = new $this->fields["1"]();
    $v->set_value($value);
    $this->_set_arr_value("1", $index, $v);
  }
  function remove_last_video_format()
  {
    $this->_remove_last_arr_value("1");
  }
  function video_format_size()
  {
    return $this->_get_arr_size("1");
  }
  function content($offset)
  {
    return $this->_get_arr_value("2", $offset);
  }
  function add_content()
  {
    return $this->_add_arr_value("2");
  }
  function set_content($index, $value)
  {
    $this->_set_arr_value("2", $index, $value);
  }
  function remove_last_content()
  {
    $this->_remove_last_arr_value("2");
  }
  function content_size()
  {
    return $this->_get_arr_size("2");
  }
  function videoad_start_delay()
  {
    return $this->_get_value("3");
  }
  function set_videoad_start_delay($value)
  {
    return $this->_set_value("3", $value);
  }
  function videoad_section_start_delay()
  {
    return $this->_get_value("4");
  }
  function set_videoad_section_start_delay($value)
  {
    return $this->_set_value("4", $value);
  }
  function min_ad_duration()
  {
    return $this->_get_value("5");
  }
  function set_min_ad_duration($value)
  {
    return $this->_set_value("5", $value);
  }
  function max_ad_duration()
  {
    return $this->_get_value("6");
  }
  function set_max_ad_duration($value)
  {
    return $this->_set_value("6", $value);
  }
  function protocol()
  {
    return $this->_get_value("7");
  }
  function set_protocol($value)
  {
    return $this->_set_value("7", $value);
  }
}
class BidRequest_Deal_PreferredDeal extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "dealid";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "advertiser_ids";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = array();
    $this->names_type["3"] = "fix_cpm_price";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
  }
  function dealid()
  {
    return $this->_get_value("1");
  }
  function set_dealid($value)
  {
    return $this->_set_value("1", $value);
  }
  function advertiser_ids($offset)
  {
    $v = $this->_get_arr_value("2", $offset);
    return $v->get_value();
  }
  function append_advertiser_ids($value)
  {
    $v = $this->_add_arr_value("2");
    $v->set_value($value);
  }
  function set_advertiser_ids($index, $value)
  {
    $v = new $this->fields["2"]();
    $v->set_value($value);
    $this->_set_arr_value("2", $index, $v);
  }
  function remove_last_advertiser_ids()
  {
    $this->_remove_last_arr_value("2");
  }
  function advertiser_ids_size()
  {
    return $this->_get_arr_size("2");
  }
  function fix_cpm_price()
  {
    return $this->_get_value("3");
  }
  function set_fix_cpm_price($value)
  {
    return $this->_set_value("3", $value);
  }
}
class BidRequest_Deal_PrivateAuction_BuyerRule extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "advertiser_ids";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = array();
    $this->names_type["2"] = "min_cpm_price";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
  }
  function advertiser_ids($offset)
  {
    $v = $this->_get_arr_value("1", $offset);
    return $v->get_value();
  }
  function append_advertiser_ids($value)
  {
    $v = $this->_add_arr_value("1");
    $v->set_value($value);
  }
  function set_advertiser_ids($index, $value)
  {
    $v = new $this->fields["1"]();
    $v->set_value($value);
    $this->_set_arr_value("1", $index, $v);
  }
  function remove_last_advertiser_ids()
  {
    $this->_remove_last_arr_value("1");
  }
  function advertiser_ids_size()
  {
    return $this->_get_arr_size("1");
  }
  function min_cpm_price()
  {
    return $this->_get_value("2");
  }
  function set_min_cpm_price($value)
  {
    return $this->_set_value("2", $value);
  }
}
class BidRequest_Deal_PrivateAuction extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "dealid";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "buyer_rules";
    $this->fields["2"] = "BidRequest_Deal_PrivateAuction_BuyerRule";
    $this->values["2"] = array();
  }
  function dealid()
  {
    return $this->_get_value("1");
  }
  function set_dealid($value)
  {
    return $this->_set_value("1", $value);
  }
  function buyer_rules($offset)
  {
    return $this->_get_arr_value("2", $offset);
  }
  function add_buyer_rules()
  {
    return $this->_add_arr_value("2");
  }
  function set_buyer_rules($index, $value)
  {
    $this->_set_arr_value("2", $index, $value);
  }
  function remove_last_buyer_rules()
  {
    $this->_remove_last_arr_value("2");
  }
  function buyer_rules_size()
  {
    return $this->_get_arr_size("2");
  }
}
class BidRequest_Deal extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "prefer_deal";
    $this->fields["1"] = "BidRequest_Deal_PreferredDeal";
    $this->values["1"] = "";
    $this->names_type["2"] = "priv_auc";
    $this->fields["2"] = "BidRequest_Deal_PrivateAuction";
    $this->values["2"] = "";
  }
  function prefer_deal($offset)
  {
    return $this->_get_arr_value("1", $offset);
  }
  function add_prefer_deal()
  {
    return $this->_add_arr_value("1");
  }
  function set_prefer_deal($index, $value)
  {
    $this->_set_arr_value("1", $index, $value);
  }
  function remove_last_prefer_deal()
  {
    $this->_remove_last_arr_value("1");
  }
  function prefer_deal_size()
  {
    return $this->_get_arr_size("1");
  }
  function priv_auc($offset)
  {
    return $this->_get_arr_value("2", $offset);
  }
  function add_priv_auc()
  {
    return $this->_add_arr_value("2");
  }
  function set_priv_auc($index, $value)
  {
    $this->_set_arr_value("2", $index, $value);
  }
  function remove_last_priv_auc()
  {
    $this->_remove_last_arr_value("2");
  }
  function priv_auc_size()
  {
    return $this->_get_arr_size("2");
  }
}
class BidRequest extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "version";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "bid";
    $this->fields["2"] = "PBString";
    $this->values["2"] = "";
    $this->names_type["11"] = "is_test";
    $this->fields["11"] = "PBInt";
    $this->values["11"] = "";
    $this->values["11"] = new PBInt();
    $this->values["11"]->value = 0;
    $this->names_type["12"] = "is_ping";
    $this->fields["12"] = "PBInt";
    $this->values["12"] = "";
    $this->values["12"] = new PBInt();
    $this->values["12"]->value = 0;
    $this->names_type["3"] = "tid";
    $this->fields["3"] = "PBString";
    $this->values["3"] = "";
    $this->names_type["4"] = "ip";
    $this->fields["4"] = "PBString";
    $this->values["4"] = "";
    $this->names_type["5"] = "user_agent";
    $this->fields["5"] = "PBString";
    $this->values["5"] = "";
    $this->names_type["13"] = "timezone_offset";
    $this->fields["13"] = "PBInt";
    $this->values["13"] = "";
    $this->names_type["14"] = "user_vertical";
    $this->fields["14"] = "PBInt";
    $this->values["14"] = array();
    $this->names_type["19"] = "tid_version";
    $this->fields["19"] = "PBInt";
    $this->values["19"] = "";
    $this->names_type["6"] = "excluded_click_through_url";
    $this->fields["6"] = "PBString";
    $this->values["6"] = array();
    $this->names_type["7"] = "url";
    $this->fields["7"] = "PBString";
    $this->values["7"] = "";
    $this->names_type["8"] = "category";
    $this->fields["8"] = "PBInt";
    $this->values["8"] = "";
    $this->names_type["9"] = "adx_type";
    $this->fields["9"] = "PBInt";
    $this->values["9"] = "";
    $this->values["9"] = new PBInt();
    $this->values["9"]->value = 0;
    $this->names_type["15"] = "anonymous_id";
    $this->fields["15"] = "PBString";
    $this->values["15"] = "";
    $this->names_type["16"] = "detected_language";
    $this->fields["16"] = "PBString";
    $this->values["16"] = "";
    $this->names_type["18"] = "category_version";
    $this->fields["18"] = "PBInt";
    $this->values["18"] = "";
    $this->names_type["10"] = "adzinfo";
    $this->fields["10"] = "BidRequest_AdzInfo";
    $this->values["10"] = array();
    $this->names_type["17"] = "excluded_sensitive_category";
    $this->fields["17"] = "PBInt";
    $this->values["17"] = array();
    $this->names_type["20"] = "excluded_ad_category";
    $this->fields["20"] = "PBInt";
    $this->values["20"] = array();
    $this->names_type["21"] = "hosted_match_data";
    $this->fields["21"] = "PBString";
    $this->values["21"] = "";
    $this->names_type["22"] = "user_attribute";
    $this->fields["22"] = "BidRequest_UserAttribute";
    $this->values["22"] = array();
    $this->names_type["23"] = "page_session_id";
    $this->fields["23"] = "PBString";
    $this->values["23"] = "";
    $this->names_type["24"] = "private_info";
    $this->fields["24"] = "BidRequest_PrivateInfo";
    $this->values["24"] = array();
    $this->names_type["25"] = "mobile";
    $this->fields["25"] = "BidRequest_Mobile";
    $this->values["25"] = "";
    $this->names_type["26"] = "content_categories";
    $this->fields["26"] = "BidRequest_ContentCategory";
    $this->values["26"] = array();
    $this->names_type["27"] = "video";
    $this->fields["27"] = "BidRequest_Video";
    $this->values["27"] = "";
    $this->names_type["28"] = "aid";
    $this->fields["28"] = "PBString";
    $this->values["28"] = "";
    $this->names_type["29"] = "deals";
    $this->fields["29"] = "BidRequest_Deal";
    $this->values["29"] = array();
  }
  function version()
  {
    return $this->_get_value("1");
  }
  function set_version($value)
  {
    return $this->_set_value("1", $value);
  }
  function bid()
  {
    return $this->_get_value("2");
  }
  function set_bid($value)
  {
    return $this->_set_value("2", $value);
  }
  function is_test()
  {
    return $this->_get_value("11");
  }
  function set_is_test($value)
  {
    return $this->_set_value("11", $value);
  }
  function is_ping()
  {
    return $this->_get_value("12");
  }
  function set_is_ping($value)
  {
    return $this->_set_value("12", $value);
  }
  function tid()
  {
    return $this->_get_value("3");
  }
  function set_tid($value)
  {
    return $this->_set_value("3", $value);
  }
  function ip()
  {
    return $this->_get_value("4");
  }
  function set_ip($value)
  {
    return $this->_set_value("4", $value);
  }
  function user_agent()
  {
    return $this->_get_value("5");
  }
  function set_user_agent($value)
  {
    return $this->_set_value("5", $value);
  }
  function timezone_offset()
  {
    return $this->_get_value("13");
  }
  function set_timezone_offset($value)
  {
    return $this->_set_value("13", $value);
  }
  function user_vertical($offset)
  {
    $v = $this->_get_arr_value("14", $offset);
    return $v->get_value();
  }
  function append_user_vertical($value)
  {
    $v = $this->_add_arr_value("14");
    $v->set_value($value);
  }
  function set_user_vertical($index, $value)
  {
    $v = new $this->fields["14"]();
    $v->set_value($value);
    $this->_set_arr_value("14", $index, $v);
  }
  function remove_last_user_vertical()
  {
    $this->_remove_last_arr_value("14");
  }
  function user_vertical_size()
  {
    return $this->_get_arr_size("14");
  }
  function tid_version()
  {
    return $this->_get_value("19");
  }
  function set_tid_version($value)
  {
    return $this->_set_value("19", $value);
  }
  function excluded_click_through_url($offset)
  {
    $v = $this->_get_arr_value("6", $offset);
    return $v->get_value();
  }
  function append_excluded_click_through_url($value)
  {
    $v = $this->_add_arr_value("6");
    $v->set_value($value);
  }
  function set_excluded_click_through_url($index, $value)
  {
    $v = new $this->fields["6"]();
    $v->set_value($value);
    $this->_set_arr_value("6", $index, $v);
  }
  function remove_last_excluded_click_through_url()
  {
    $this->_remove_last_arr_value("6");
  }
  function excluded_click_through_url_size()
  {
    return $this->_get_arr_size("6");
  }
  function url()
  {
    return $this->_get_value("7");
  }
  function set_url($value)
  {
    return $this->_set_value("7", $value);
  }
  function category()
  {
    return $this->_get_value("8");
  }
  function set_category($value)
  {
    return $this->_set_value("8", $value);
  }
  function adx_type()
  {
    return $this->_get_value("9");
  }
  function set_adx_type($value)
  {
    return $this->_set_value("9", $value);
  }
  function anonymous_id()
  {
    return $this->_get_value("15");
  }
  function set_anonymous_id($value)
  {
    return $this->_set_value("15", $value);
  }
  function detected_language()
  {
    return $this->_get_value("16");
  }
  function set_detected_language($value)
  {
    return $this->_set_value("16", $value);
  }
  function category_version()
  {
    return $this->_get_value("18");
  }
  function set_category_version($value)
  {
    return $this->_set_value("18", $value);
  }
  function adzinfo($offset)
  {
    return $this->_get_arr_value("10", $offset);
  }
  function add_adzinfo()
  {
    return $this->_add_arr_value("10");
  }
  function set_adzinfo($index, $value)
  {
    $this->_set_arr_value("10", $index, $value);
  }
  function remove_last_adzinfo()
  {
    $this->_remove_last_arr_value("10");
  }
  function adzinfo_size()
  {
    return $this->_get_arr_size("10");
  }
  function excluded_sensitive_category($offset)
  {
    $v = $this->_get_arr_value("17", $offset);
    return $v->get_value();
  }
  function append_excluded_sensitive_category($value)
  {
    $v = $this->_add_arr_value("17");
    $v->set_value($value);
  }
  function set_excluded_sensitive_category($index, $value)
  {
    $v = new $this->fields["17"]();
    $v->set_value($value);
    $this->_set_arr_value("17", $index, $v);
  }
  function remove_last_excluded_sensitive_category()
  {
    $this->_remove_last_arr_value("17");
  }
  function excluded_sensitive_category_size()
  {
    return $this->_get_arr_size("17");
  }
  function excluded_ad_category($offset)
  {
    $v = $this->_get_arr_value("20", $offset);
    return $v->get_value();
  }
  function append_excluded_ad_category($value)
  {
    $v = $this->_add_arr_value("20");
    $v->set_value($value);
  }
  function set_excluded_ad_category($index, $value)
  {
    $v = new $this->fields["20"]();
    $v->set_value($value);
    $this->_set_arr_value("20", $index, $v);
  }
  function remove_last_excluded_ad_category()
  {
    $this->_remove_last_arr_value("20");
  }
  function excluded_ad_category_size()
  {
    return $this->_get_arr_size("20");
  }
  function hosted_match_data()
  {
    return $this->_get_value("21");
  }
  function set_hosted_match_data($value)
  {
    return $this->_set_value("21", $value);
  }
  function user_attribute($offset)
  {
    return $this->_get_arr_value("22", $offset);
  }
  function add_user_attribute()
  {
    return $this->_add_arr_value("22");
  }
  function set_user_attribute($index, $value)
  {
    $this->_set_arr_value("22", $index, $value);
  }
  function remove_last_user_attribute()
  {
    $this->_remove_last_arr_value("22");
  }
  function user_attribute_size()
  {
    return $this->_get_arr_size("22");
  }
  function page_session_id()
  {
    return $this->_get_value("23");
  }
  function set_page_session_id($value)
  {
    return $this->_set_value("23", $value);
  }
  function private_info($offset)
  {
    return $this->_get_arr_value("24", $offset);
  }
  function add_private_info()
  {
    return $this->_add_arr_value("24");
  }
  function set_private_info($index, $value)
  {
    $this->_set_arr_value("24", $index, $value);
  }
  function remove_last_private_info()
  {
    $this->_remove_last_arr_value("24");
  }
  function private_info_size()
  {
    return $this->_get_arr_size("24");
  }
  function mobile($offset)
  {
    return $this->_get_arr_value("25", $offset);
  }
  function add_mobile()
  {
    return $this->_add_arr_value("25");
  }
  function set_mobile($index, $value)
  {
    $this->_set_arr_value("25", $index, $value);
  }
  function remove_last_mobile()
  {
    $this->_remove_last_arr_value("25");
  }
  function mobile_size()
  {
    return $this->_get_arr_size("25");
  }
  function content_categories($offset)
  {
    return $this->_get_arr_value("26", $offset);
  }
  function add_content_categories()
  {
    return $this->_add_arr_value("26");
  }
  function set_content_categories($index, $value)
  {
    $this->_set_arr_value("26", $index, $value);
  }
  function remove_last_content_categories()
  {
    $this->_remove_last_arr_value("26");
  }
  function content_categories_size()
  {
    return $this->_get_arr_size("26");
  }
  function video($offset)
  {
    return $this->_get_arr_value("27", $offset);
  }
  function add_video()
  {
    return $this->_add_arr_value("27");
  }
  function set_video($index, $value)
  {
    $this->_set_arr_value("27", $index, $value);
  }
  function remove_last_video()
  {
    $this->_remove_last_arr_value("27");
  }
  function video_size()
  {
    return $this->_get_arr_size("27");
  }
  function aid()
  {
    return $this->_get_value("28");
  }
  function set_aid($value)
  {
    return $this->_set_value("28", $value);
  }
  function deals($offset)
  {
    return $this->_get_arr_value("29", $offset);
  }
  function add_deals()
  {
    return $this->_add_arr_value("29");
  }
  function set_deals($index, $value)
  {
    $this->_set_arr_value("29", $index, $value);
  }
  function remove_last_deals()
  {
    $this->_remove_last_arr_value("29");
  }
  function deals_size()
  {
    return $this->_get_arr_size("29");
  }
}
class BidResponse_Ads extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "adzinfo_id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "max_cpm_price";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "ad_bid_count_idx";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->names_type["4"] = "html_snippet";
    $this->fields["4"] = "PBString";
    $this->values["4"] = "";
    $this->names_type["5"] = "click_through_url";
    $this->fields["5"] = "PBString";
    $this->values["5"] = array();
    $this->names_type["6"] = "category";
    $this->fields["6"] = "PBInt";
    $this->values["6"] = array();
    $this->names_type["7"] = "creative_type";
    $this->fields["7"] = "PBInt";
    $this->values["7"] = array();
    $this->names_type["8"] = "network_guid";
    $this->fields["8"] = "PBString";
    $this->values["8"] = "";
    $this->names_type["9"] = "extend_data";
    $this->fields["9"] = "PBString";
    $this->values["9"] = "";
    $this->names_type["10"] = "destination_url";
    $this->fields["10"] = "PBString";
    $this->values["10"] = array();
    $this->names_type["11"] = "creative_id";
    $this->fields["11"] = "PBString";
    $this->values["11"] = "";
    $this->names_type["12"] = "resource_address";
    $this->fields["12"] = "PBString";
    $this->values["12"] = "";
    $this->names_type["13"] = "feedback_address";
    $this->fields["13"] = "PBString";
    $this->values["13"] = "";
    $this->names_type["14"] = "video_snippet";
    $this->fields["14"] = "PBString";
    $this->values["14"] = "";
    $this->names_type["15"] = "mobile_creative";
    $this->fields["15"] = "MobileCreative";
    $this->values["15"] = "";
    $this->names_type["16"] = "dealid";
    $this->fields["16"] = "PBInt";
    $this->values["16"] = "";
    $this->names_type["17"] = "advertiser_ids";
    $this->fields["17"] = "PBInt";
    $this->values["17"] = array();
    $this->names_type["18"] = "native_template_id";
    $this->fields["18"] = "PBString";
    $this->values["18"] = "";
  }
  function adzinfo_id()
  {
    return $this->_get_value("1");
  }
  function set_adzinfo_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function max_cpm_price()
  {
    return $this->_get_value("2");
  }
  function set_max_cpm_price($value)
  {
    return $this->_set_value("2", $value);
  }
  function ad_bid_count_idx()
  {
    return $this->_get_value("3");
  }
  function set_ad_bid_count_idx($value)
  {
    return $this->_set_value("3", $value);
  }
  function html_snippet()
  {
    return $this->_get_value("4");
  }
  function set_html_snippet($value)
  {
    return $this->_set_value("4", $value);
  }
  function click_through_url($offset)
  {
    $v = $this->_get_arr_value("5", $offset);
    return $v->get_value();
  }
  function append_click_through_url($value)
  {
    $v = $this->_add_arr_value("5");
    $v->set_value($value);
  }
  function set_click_through_url($index, $value)
  {
    $v = new $this->fields["5"]();
    $v->set_value($value);
    $this->_set_arr_value("5", $index, $v);
  }
  function remove_last_click_through_url()
  {
    $this->_remove_last_arr_value("5");
  }
  function click_through_url_size()
  {
    return $this->_get_arr_size("5");
  }
  function category($offset)
  {
    $v = $this->_get_arr_value("6", $offset);
    return $v->get_value();
  }
  function append_category($value)
  {
    $v = $this->_add_arr_value("6");
    $v->set_value($value);
  }
  function set_category($index, $value)
  {
    $v = new $this->fields["6"]();
    $v->set_value($value);
    $this->_set_arr_value("6", $index, $v);
  }
  function remove_last_category()
  {
    $this->_remove_last_arr_value("6");
  }
  function category_size()
  {
    return $this->_get_arr_size("6");
  }
  function creative_type($offset)
  {
    $v = $this->_get_arr_value("7", $offset);
    return $v->get_value();
  }
  function append_creative_type($value)
  {
    $v = $this->_add_arr_value("7");
    $v->set_value($value);
  }
  function set_creative_type($index, $value)
  {
    $v = new $this->fields["7"]();
    $v->set_value($value);
    $this->_set_arr_value("7", $index, $v);
  }
  function remove_last_creative_type()
  {
    $this->_remove_last_arr_value("7");
  }
  function creative_type_size()
  {
    return $this->_get_arr_size("7");
  }
  function network_guid()
  {
    return $this->_get_value("8");
  }
  function set_network_guid($value)
  {
    return $this->_set_value("8", $value);
  }
  function extend_data()
  {
    return $this->_get_value("9");
  }
  function set_extend_data($value)
  {
    return $this->_set_value("9", $value);
  }
  function destination_url($offset)
  {
    $v = $this->_get_arr_value("10", $offset);
    return $v->get_value();
  }
  function append_destination_url($value)
  {
    $v = $this->_add_arr_value("10");
    $v->set_value($value);
  }
  function set_destination_url($index, $value)
  {
    $v = new $this->fields["10"]();
    $v->set_value($value);
    $this->_set_arr_value("10", $index, $v);
  }
  function remove_last_destination_url()
  {
    $this->_remove_last_arr_value("10");
  }
  function destination_url_size()
  {
    return $this->_get_arr_size("10");
  }
  function creative_id()
  {
    return $this->_get_value("11");
  }
  function set_creative_id($value)
  {
    return $this->_set_value("11", $value);
  }
  function resource_address()
  {
    return $this->_get_value("12");
  }
  function set_resource_address($value)
  {
    return $this->_set_value("12", $value);
  }
  function feedback_address()
  {
    return $this->_get_value("13");
  }
  function set_feedback_address($value)
  {
    return $this->_set_value("13", $value);
  }
  function video_snippet()
  {
    return $this->_get_value("14");
  }
  function set_video_snippet($value)
  {
    return $this->_set_value("14", $value);
  }
  function mobile_creative($offset)
  {
    return $this->_get_arr_value("15", $offset);
  }
  function add_mobile_creative()
  {
    return $this->_add_arr_value("15");
  }
  function set_mobile_creative($index, $value)
  {
    $this->_set_arr_value("15", $index, $value);
  }
  function remove_last_mobile_creative()
  {
    $this->_remove_last_arr_value("15");
  }
  function mobile_creative_size()
  {
    return $this->_get_arr_size("15");
  }
  function dealid()
  {
    return $this->_get_value("16");
  }
  function set_dealid($value)
  {
    return $this->_set_value("16", $value);
  }
  function advertiser_ids($offset)
  {
    $v = $this->_get_arr_value("17", $offset);
    return $v->get_value();
  }
  function append_advertiser_ids($value)
  {
    $v = $this->_add_arr_value("17");
    $v->set_value($value);
  }
  function set_advertiser_ids($index, $value)
  {
    $v = new $this->fields["17"]();
    $v->set_value($value);
    $this->_set_arr_value("17", $index, $v);
  }
  function remove_last_advertiser_ids()
  {
    $this->_remove_last_arr_value("17");
  }
  function advertiser_ids_size()
  {
    return $this->_get_arr_size("17");
  }
  function native_template_id()
  {
    return $this->_get_value("18");
  }
  function set_native_template_id($value)
  {
    return $this->_set_value("18", $value);
  }
}
class BidResponse extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "version";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "bid";
    $this->fields["2"] = "PBString";
    $this->values["2"] = "";
    $this->names_type["3"] = "ads";
    $this->fields["3"] = "BidResponse_Ads";
    $this->values["3"] = array();
  }
  function version()
  {
    return $this->_get_value("1");
  }
  function set_version($value)
  {
    return $this->_set_value("1", $value);
  }
  function bid()
  {
    return $this->_get_value("2");
  }
  function set_bid($value)
  {
    return $this->_set_value("2", $value);
  }
  function ads($offset)
  {
    return $this->_get_arr_value("3", $offset);
  }
  function add_ads()
  {
    return $this->_add_arr_value("3");
  }
  function set_ads($index, $value)
  {
    $this->_set_arr_value("3", $index, $value);
  }
  function remove_last_ads()
  {
    $this->_remove_last_arr_value("3");
  }
  function ads_size()
  {
    return $this->_get_arr_size("3");
  }
}
class BidResult_Res extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "adzinfo_id";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "ad_bid_count_idx";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "result_code";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->values["3"] = new PBInt();
    $this->values["3"]->value = 0;
    $this->names_type["4"] = "result_price";
    $this->fields["4"] = "PBInt";
    $this->values["4"] = "";
    $this->names_type["5"] = "extend_data";
    $this->fields["5"] = "PBString";
    $this->values["5"] = "";
  }
  function adzinfo_id()
  {
    return $this->_get_value("1");
  }
  function set_adzinfo_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function ad_bid_count_idx()
  {
    return $this->_get_value("2");
  }
  function set_ad_bid_count_idx($value)
  {
    return $this->_set_value("2", $value);
  }
  function result_code()
  {
    return $this->_get_value("3");
  }
  function set_result_code($value)
  {
    return $this->_set_value("3", $value);
  }
  function result_price()
  {
    return $this->_get_value("4");
  }
  function set_result_price($value)
  {
    return $this->_set_value("4", $value);
  }
  function extend_data()
  {
    return $this->_get_value("5");
  }
  function set_extend_data($value)
  {
    return $this->_set_value("5", $value);
  }
}
class BidResult extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "version";
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->names_type["2"] = "bid";
    $this->fields["2"] = "PBString";
    $this->values["2"] = "";
    $this->names_type["3"] = "res";
    $this->fields["3"] = "BidResult_Res";
    $this->values["3"] = array();
  }
  function version()
  {
    return $this->_get_value("1");
  }
  function set_version($value)
  {
    return $this->_set_value("1", $value);
  }
  function bid()
  {
    return $this->_get_value("2");
  }
  function set_bid($value)
  {
    return $this->_set_value("2", $value);
  }
  function res($offset)
  {
    return $this->_get_arr_value("3", $offset);
  }
  function add_res()
  {
    return $this->_add_arr_value("3");
  }
  function set_res($index, $value)
  {
    $this->_set_arr_value("3", $index, $value);
  }
  function remove_last_res()
  {
    $this->_remove_last_arr_value("3");
  }
  function res_size()
  {
    return $this->_get_arr_size("3");
  }
}
?>
