<?php
require_once(dirname(__FILE__)."/pb_proto_tanx.php");

$a = new MobileCreative_Creative_Attr();
$a->set_name("中国");
$a->set_value("vvvaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaavv");

//print_r($a);
//print_r($a->SerializeToString());
//print_r( base64_encode($a->SerializeToString()) );

$b = $a->SerializeToString();

$c= new MobileCreative_Creative_Attr();

//$c->ParseFromArray($b);
$c->ParseFromString($b);


print_r($c->name());

?>
