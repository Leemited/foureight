<?php
include_once ("../../../common.php");

$result = 0;

$sql = "select *,o.mb_id as od_mb_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where p.pd_id = '{$pd_id}' and o.od_cancel_status = 0 ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    if($row["pd_type"]==1){
        if($row['od_mb_id']==$member["mb_id"]){
            $result = "2";
            break;
        }
        if($row["pd_status"]!=0){
            $result = "1";
            break;
        }
    }
    if($row['od_mb_id']==$member["mb_id"]){
        if($row["od_fin_status"]==0 && $row["od_status"]==1){
            $result = "2";
            break;
        }
    }
    /*if()
    if($row["od_status"]==1){
        $result = "1";
        break;
    }*/
}

echo $result;
?>