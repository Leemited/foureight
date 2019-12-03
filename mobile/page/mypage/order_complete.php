<?php
include_once ("../../../common.php");

//if($type==1){
    $sql = "select *,p.mb_id as mb_id from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where p.pd_id = '{$pd_id}'";
    $pd = sql_fetch($sql);

    if($pd["pd_type"]==2){
        $set = " , od_step = 2";
    }

    $sql = "update `order` set od_fin_status = 1 , od_fin_datetime = now() {$set} where od_id = '{$od_id}'";
    if(sql_query($sql)){

        if($pd["pd_type"]==1) {
            //상품 판매완료 처리
            $sql = "update `product` set pd_status = 9 where pd_id = '{$pd_id}'";
            sql_query($sql);
        }

        if($pd["pd_images"]) {
            $imgs = explode(",",$pd["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }
        //알림 보내기
        if($pd["regid"]) {
            //send_FCM('fcmid','타이틀','내용','유알엘','체널','체널명','받는아이디','게시글번호','이미지');
            send_FCM($pd["regid"], $pd["pd_tag"], "의 거래가 완료 되었습니다.", G5_URL . "/mobile/page/mypage/mypage_order_complete.php?od_cate=1&pd_type=".$pd["pd_type"], "fcm_buy_channel", "구매알림", $pd["mb_id"], $pd_id, $img);
        }

        alert("거래가 완료되었습니다.");
    }
//}

?>


