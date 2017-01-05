<?php
require_once(dirname(__FILE__)."/../src/util/protocol/http.php");

logging::info(http::get("http://10.125.51.188:3224/aaaa"));
logging::info(http::get("http://127.0.0.1:3224/"));

?>
