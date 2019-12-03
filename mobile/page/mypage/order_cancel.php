<?php
include_once ("../../../common.php");
if($type==0){//요청
    $sql = "update `order` set od_cancel_status = 1 where od_id ='{$od_id}'";
    if(sql_query($sql)){//판매자에게 요청알림
        $sql = "select *,p.mb_id as pd_mb_id from `order` as o left join `product` as p ON o.pd_id = p.pd_id where o.od_id = '{$od_id}'";
        $pro = sql_fetch($sql);
        
        $mb = get_member($pro["pd_mb_id"]);
        
        if($mb["regid"]){
            $img="";
            if($pro["pd_images"]){
                $imgs = explode(",", $pro["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }

            send_FCM($mb["regid"], "결제 취소 요청", $pro["pd_tag"] . "의 결제취소 요청입니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pro["pd_type"], 'fcm_buy_channel', '구매일림', $mb["mb_id"], $pro['pd_id'], $img);

        }

        alert("결제취소 요청하였습니다.",G5_MOBILE_URL."/page/mypage/mypage_order.php?od_cate=2&pd_type=".$pro["pd_type"]);
        
    }else{
        alert("요청할 수 없는 상태입니다.");
    }
}else if($type==1){
    $sql = "update `order` set od_cancel_status = -1 where od_id ='{$od_id}'";
    if(sql_query($sql)){//구매자 알림
        $sql = "select *,o.mb_id as mb_id from `order` as o left join `product` as p ON o.pd_id = p.pd_id where o.od_id = '{$od_id}'";
        $pro = sql_fetch($sql);

        $mb = get_member($pro["mb_id"]);

        if($mb["regid"]){
            $img="";
            if($pro["pd_images"]){
                $imgs = explode(",", $pro["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }

            send_FCM($mb["regid"], "결제 취소 요청", $pro["pd_tag"] . "의 결제취소 요청이 거절되었습니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=' . $pro["pd_type"], 'fcm_buy_channel', '구매일림', $mb["mb_id"], $pro['pd_id'], $img);

        }

        alert("결제취소요청을 거절하였습니다.",G5_MOBILE_URL."/page/mypage/mypage_order.php?od_cate=1&pd_type=".$pro["pd_type"]);

    }else{
        alert("거절 할 수 없는 상태입니다.");
    }
}else if($type==2){//결제 취소 안될경우 관리자에게 요청(PG사에서 문제로 인해)
    //todo:결제취소 해주세요.
}
?>


