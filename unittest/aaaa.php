<?php
require_once("../src/basa.php");
$json = '{"version":1,"biz_30day":3}';
logging::debug(11111111);
$str = pb::SerializeFromJson($json,"Simba.proto","SimbaGoods", $topath="./");
logging::debug('2222222');
var_dump($str);
?>
