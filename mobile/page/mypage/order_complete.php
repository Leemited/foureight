<?php
include_once ("../../../common.php");

//if($type==1){
    $sql = "select *,p.mb_id as mb_id from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where p.pd_id = '{$pd_id}'";
    $pd = sql_fetch($sql);

    if($pd_type==2){
        $set = " , od_step = 2";
    }

    $sql = "update `order` set od_fin_status = 1 , od_fin_datetime = now() {$set} where od_id = '{$od_id}'";
    if(sql_query($sql)){

        if($pd_type==1) {
            //상품 판매완료 처리
            $sql = "update `product` set pd_status = 4 where pd_id = '{$pd_id}'";
            sql_query($sql);
        }

        if($pd["pd_images"]) {
            $imgs = explode(",",$pd["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }
        //알림 보내기
        if($pd["regid"]) {
            //send_FCM('fcmid','타이틀','내용','유알엘','체널','체널명','받는아이디','게시글번호','이미지');
            send_FCM($pd["regid"], "48 거래 완료", cut_str($pd["pd_tag"],10,"...")."의 거래가 완료 되었습니다.\r\n거래대금 정산은 4일이내에 자동 처리됩니다.\r\n정산계좌가 등록되지 않으면 지연될 수 있습니다.", G5_URL . "/mobile/page/mypage/mypage_order_complete.php?od_cate=1&pd_type=".$pd["pd_type"], "fcm_buy_channel", "구매알림", $pd["mb_id"], $pd_id, $img);
        }

        alert("거래가 완료되었습니다.",G5_MOBILE_URL."/page/mypage/mypage_order_complete.php?od_cate=2&pd_type=".$pd_type);
    }
//}

?>


