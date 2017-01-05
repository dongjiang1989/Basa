<?php
require_once('/home/dongjiang.dongj/Source/basa/src/tools/protobuf/parser/../message/pb_message.php');
class Person_PhoneType extends PBEnum
{
  const MOBILE  = 0;
  const HOME  = 1;
  const WORK  = 2;
}
class Person_PhoneNumber extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "aaa";
    $this->fields["1"] = "PBString";
    $this->values["1"] = "";
    $this->names_type["2"] = "bb";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
  }
  function aaa()
  {
    return $this->_get_value("1");
  }
  function set_aaa($value)
  {
    return $this->_set_value("1", $value);
  }
  function bb()
  {
    return $this->_get_value("2");
  }
  function set_bb($value)
  {
    return $this->_set_value("2", $value);
  }
}
class Person extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  var $names_type = array();
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->names_type["1"] = "name";
    $this->fields["1"] = "PBString";
    $this->values["1"] = "";
    $this->names_type["2"] = "id";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->names_type["3"] = "email";
    $this->fields["3"] = "PBString";
    $this->values["3"] = "";
    $this->names_type["8"] = "ok";
    $this->fields["8"] = "PBString";
    $this->values["8"] = array();
    $this->names_type["7"] = "pt";
    $this->fields["7"] = "Person_PhoneType";
    $this->values["7"] = array();
    $this->names_type["4"] = "phone";
    $this->fields["4"] = "Person_PhoneNumber";
    $this->values["4"] = array();
    $this->names_type["6"] = "phone1";
    $this->fields["6"] = "Person_PhoneNumber";
    $this->values["6"] = array();
  }
  function name()
  {
    return $this->_get_value("1");
  }
  function set_name($value)
  {
    return $this->_set_value("1", $value);
  }
  function id()
  {
    return $this->_get_value("2");
  }
  function set_id($value)
  {
    return $this->_set_value("2", $value);
  }
  function email()
  {
    return $this->_get_value("3");
  }
  function set_email($value)
  {
    return $this->_set_value("3", $value);
  }
  function ok($offset)
  {
    $v = $this->_get_arr_value("8", $offset);
    return $v->get_value();
  }
  function append_ok($value)
  {
    $v = $this->_add_arr_value("8");
    $v->set_value($value);
  }
  function set_ok($index, $value)
  {
    $v = new $this->fields["8"]();
    $v->set_value($value);
    $this->_set_arr_value("8", $index, $v);
  }
  function remove_last_ok()
  {
    $this->_remove_last_arr_value("8");
  }
  function ok_size()
  {
    return $this->_get_arr_size("8");
  }
  function pt($offset)
  {
    $v = $this->_get_arr_value("7", $offset);
    return $v->get_value();
  }
  function append_pt($value)
  {
    $v = $this->_add_arr_value("7");
    $v->set_value($value);
  }
  function set_pt($index, $value)
  {
    $v = new $this->fields["7"]();
    $v->set_value($value);
    $this->_set_arr_value("7", $index, $v);
  }
  function remove_last_pt()
  {
    $this->_remove_last_arr_value("7");
  }
  function pt_size()
  {
    return $this->_get_arr_size("7");
  }
  function phone($offset)
  {
    return $this->_get_arr_value("4", $offset);
  }
  function add_phone()
  {
    return $this->_add_arr_value("4");
  }
  function set_phone($index, $value)
  {
    $this->_set_arr_value("4", $index, $value);
  }
  function remove_last_phone()
  {
    $this->_remove_last_arr_value("4");
  }
  function phone_size()
  {
    return $this->_get_arr_size("4");
  }
  function phone1($offset)
  {
    return $this->_get_arr_value("6", $offset);
  }
  function add_phone1()
  {
    return $this->_add_arr_value("6");
  }
  function set_phone1($index, $value)
  {
    $this->_set_arr_value("6", $index, $value);
  }
  function remove_last_phone1()
  {
    $this->_remove_last_arr_value("6");
  }
  function phone1_size()
  {
    return $this->_get_arr_size("6");
  }
}
?>