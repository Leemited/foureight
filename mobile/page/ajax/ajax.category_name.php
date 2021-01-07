<?php
include_once("../../../common.php");

$sql = "select * from `categorys` where ca_id = '{$ca_id}'";
$cate = sql_fetch($sql);

echo $cate["cate_name"];