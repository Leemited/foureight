<?php
include_once("../../../common.php");

$sql = "select * from `categorys` where cate_name = '{$cate1}' and cate_depth = 1 ";
$cate_tag = sql_fetch($sql);

echo $cate_tag["cate_tag"];