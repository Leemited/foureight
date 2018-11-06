<?php
include_once ("../../../common.php");

if(!$pd_type){
    echo "5";
    return false;
}

if($pd_type==1){
    if (!$mb_id) {
        echo "0";
        return false;
    }
    if ($pricing_content == "") {
        echo "3";
        return false;
    }
    if($pricing_price == ""){
        echo "2";
        return false;
    }

    $sql = "insert into `product_pricing` set pd_id = {$pd_id}, pricing_content = '{$pricing_content}', pricing_price = {$pricing_price}, pd_type = {$pd_type}, sign_date = now(), mb_id = '{$mb_id}'";

    if (sql_query($sql)) {

    } else {
        echo "5";
    }
}
if($pd_type==2) {
    if (!$mb_id) {
        echo "0";
        return false;
    }
    if (!$pd_id) {
        echo "1";
        return false;
    }
    if ($pricing_pd_id == "") {
        echo "2";
        return false;
    }
    if ($pricing_content == "") {
        echo "3";
        return false;
    }

    $sql = "insert into `product_pricing` set pd_id = {$pd_id}, pricing_content = '{$pricing_content}', pricing_pd_id = {$pricing_pd_id}, pd_type = {$pd_type}, sign_date = now(), mb_id = '{$mb_id}'";

    if (sql_query($sql)) {
        echo "4";
    } else {
        echo "5";
    }
}