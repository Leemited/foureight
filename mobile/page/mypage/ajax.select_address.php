<?php
include_once ("../../../common.php");

$sql = "select * from `my_address` where id= {$id}";
$myaddress = sql_fetch($sql);

echo json_encode($myaddress);

?>