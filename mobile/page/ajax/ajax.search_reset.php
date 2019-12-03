<?php
include_once ("../../../common.php");

unset($_SESSION["type2"]);
unset($_SESSION["cate"]);
unset($_SESSION["cate2"]);
unset($_SESSION["stx"]);
unset($_SESSION["order_sort"]);
unset($_SESSION["order_sort_active"]);
unset($_SESSION["priceFrom"]);
unset($_SESSION["priceTo"]);
unset($_SESSION["pd_price_type"]);
$_SESSION["pd_price_type1"]=0;
$_SESSION["pd_price_type2"]=1;
$_SESSION["pd_price_type3"]=2;
unset($_SESSION["pd_timeFrom"]);
unset($_SESSION["pd_timeTo"]);
unset($_SESSION["pd_timetype"]);
unset($_SESSION["mb_level"]);

echo $_SESSION["type2"].$_SESSION["cate"].$_SESSION["cate2"].$_SESSION["stx"].$_SESSION["order_sort"].$_SESSION["order_sort_active"].$_SESSION["priceFrom"].$_SESSION["priceTo"].$_SESSION["pd_price_type"].$_SESSION["pd_timeFrom"].$_SESSION["pd_timeTo"].$_SESSION["pd_timetype"].$_SESSION["mb_level"];
?>