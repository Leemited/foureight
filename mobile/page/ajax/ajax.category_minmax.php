<?php
include_once("../../../common.php");

$sql = "select MAX(pd_price) as max, MIN(pd_price) as min from `product` where pd_cate = '{$cate1}' and pd_cate2 = '{$cate2}'";
$cateminmax = sql_fetch($sql);


echo json_encode($cateminmax);