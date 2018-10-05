<?php
include_once ("../../../common.php");
$key = $_REQUEST["key"];
$value = $_REQUEST["value"];

set_session($key,$value);
?>