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

    $sql = "select count(*) as cnt, id from `product_pricing` where pd_id = {$pd_id} and mb_id = '{$mb_id}'";
    $myPricing = sql_fetch($sql);

    if($myPricing["cnt"]>0){
        $sql = "update `product_pricing` set pd_id = '{$pd_id}', pricing_pd_id = '{$pricing_pd_id}', pricing_content = '{$pricing_content}', pricing_price = '{$pricing_price}', pd_type = '{$pd_type}', sign_date = now(), mb_id = '{$mb_id}',status = 0 where id = '{$myPricing["id"]}'";
    }else{
        $sql = "insert into `product_pricing` set pd_id = '{$pd_id}', pricing_pd_id = '{$pricing_pd_id}', pricing_content = '{$pricing_content}', pricing_price = '{$pricing_price}', pd_type = '{$pd_type}', sign_date = now(), status = 0, mb_id = '{$mb_id}'";
    }

    if (sql_query($sql)) {

        $sql = "select * from `product` where pd_id = '{$pd_id}'";
        $pd = sql_fetch($sql);
        if($pd["pd_images"]) {
            $imgs = explode(",",$pd["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }

        $mb = get_member($pd["mb_id"]);
        send_FCM($mb["regid"],$pd["pd_tag"],"게시물에 제시/딜 요청등록",G5_URL."/index.php?pd_id=".$pd_id."&detail=true",'pricing_set','제시/딜 알림',$mb["mb_id"],$pd["pd_id"],$img);
        echo "4";
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

    $sql = "select count(*) as cnt, id from `product_pricing` where pd_id = {$pd_id} and mb_id = '{$mb_id}'";
    $myPricing = sql_fetch($sql);

    if($myPricing["cnt"]>0){
        $sql = "update `product_pricing` set pd_id = '{$pd_id}', pricing_pd_id = '{$pricing_pd_id}', pricing_content = '{$pricing_content}', pricing_price = '{$pricing_price}', pd_type = '{$pd_type}', sign_date = now(), mb_id = '{$mb_id}',status = 0 where id = '{$myPricing["id"]}'";
    }else{
        $sql = "insert into `product_pricing` set pd_id = '{$pd_id}', pricing_pd_id = '{$pricing_pd_id}', pricing_content = '{$pricing_content}', pricing_price = '{$pricing_price}', pd_type = '{$pd_type}', sign_date = now(),status = 0, mb_id = '{$mb_id}'";
    }

    if (sql_query($sql)) {
        $sql = "select * from `product` where pd_id = '{$pd_id}'";
        $pd = sql_fetch($sql);
        if($pd["pd_images"]) {
            $imgs = explode(",",$pd["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }

        $mb = get_member($pd["mb_id"]);

        send_FCM($mb["regid"],$pd["pd_tag"],"게시물에 제시/딜 요청등록",G5_URL."/index.php?pd_id=".$pd_id."&detail=true",'pricing_set','제시/딜 알림',$mb["mb_id"],$pd["pd_id"],$img);
        echo "4";
    } else {
        echo "5";
    }
}