<?php
include_once ("../../../common.php");
$key = $_REQUEST["key"];
$value = $_REQUEST["value"];
set_session($key,$value);
/*if($key=="type1")
    $searchClass->setType1 = $value;
if($key=="type2")
    $searchClass->setType2 = $value;
if($key=="cate")
    $searchClass->setPdCate = $value;
if($key=="cate2")
    $searchClass->setPdCate2 = $value;
if($key=="cate")
    $searchClass->setPdCate = $value;*/

?>