<?php
include_once ("../../../common.php");

if($type == 2){
    $default = 1;
}else{
    $default = 0;
}
$sql = "select count(*)as cnt from `my_address` where mb_id = '{$member["mb_id"]}'";
$cnt = sql_fetch($sql);
if($cnt["cnt"] == 4){
    echo json_encode(array("result"=>0));
    return false;
}

$sql = "select count(*)as cnt from `my_address` where mb_id = '{$member["mb_id"]}' and addr_zipcode = '{$zipcode}' and addr_addrress1 = '{$addr1}' and add_address2 = '{$addr2}' and addr_mbname = '{$addr_mbname}' ";
$chk_addr = sql_fetch($sql);
if($chk_addr["cnt"] > 0 ){
    echo json_encode(array("result"=>1));
    return false;
}else{
    if($type==2){
        $sql = "update `my_address` set addr_default = 0";
        sql_query($sql);
    }
    $sql = "insert into `my_address` set mb_id = '{$member["mb_id"]}', addr_zipcode = '{$zipcode}', addr_address1 = '{$addr1}', addr_address2 = '{$addr2}' , addr_default = {$default}, addr_mbname = '{$addr_mbname}', addr_name = '{$addr_name}'";
    if(sql_query($sql)){
        echo json_encode(array("id" => sql_insert_id(),"result"=>2));
    }else{
        echo json_encode(array("result"=>3));
    }
}

?>