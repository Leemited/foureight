<?php
include_once ("./_common.php");

$sql = "update `category` set cate_status = 1 where ca_id = '{$ca_id}'";
echo $sql;