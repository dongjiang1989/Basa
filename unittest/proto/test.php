<?php
require_once("/usr/local/lib64/basa/basa.php");
$arr= array(
    "version"=>100,
    "bid"=>"123", 
    "creatives"=>array(
        array(
            "attr"=>array(
                    "name"=>"aaa", 
                    "value"=>"avx",
                    ), 
            "category"=>array(122, 1244, 12355655),
            ),
        ),
    );
$ret = pb::Serialize($arr, "tanx-bidding.proto", "MobileCreative");
#logging::debug($ret);

$ret2 = pb::Deserialize($ret, "tanx-bidding.proto", "MobileCreative");
logging::debug($ret2);
?>
