<?php
include_once ("./common.php");

//주문목록에서 결제 완료건을 가져온다
$sql = "select * from `order` as o left join `g5_member` as m on o.mb_id = m.mb_id where od_status = 1 and od_pay_status = 1 and od_pd_type = 1 and od_price > 0 and od_cancel_status <> 2 ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    if($row["od_date"]==""){continue;}
    $expired_date = date("Y-m-d",strtotime("+ 3 day",strtotime($row["od_date"])));//결제 완료일에서 3일후의 날짜
    $today = date("Y-m-d");//오늘날짜
    if($expired_date == $today && $row["delivery_extend"] == 0 && $row["delivery_name"]==""){//연장안함
        //입금완료후 3일이 지났지만 배송정보 입력전
        //판매자에게 배송처리 또는 배송연장 안내 푸시 알림 보내기 오후 3시에 1시간 간격 3번
        if($row["delivery_name"]=="" ){//배송정보 입력했니? 안했음..
            //판매자에게 푸시 보냄
            //crontab으로 매일 3시, 4시, 5시에 보내도록 설정
            //판매자 정보 가져오기
            $sql = "select *,m.mb_id as mb_id from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where p.pd_id = '{$row["pd_id"]}' ";
            $pd = sql_fetch($sql);
            $img = "";
            if($pd["pd_images"]) {
                $imgs = explode(",",$pd["pd_images"]);
                $img = G5_DATA_URL."/product/".$imgs[0];
            }
            if($pd["regid"]){//기기정보가 있으면 푸시
                send_FCM($pd["regid"],"운송장입력안내",$pd["pd_name"]."의 운송장정보를 입력해주세요. 또는 운송장입력 연장 바랍니다. 미입력시 1일이내에 자동 환불 처리가 됩니다.", G5_MOBILE_URL."/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=".$pd["pd_type"],"fcm_buy_channel","구매일림",$pd["mb_id"],$pd["pd_id"],$img);
            }
        }
    }else if($expired_date < $today && $row["delivery_extend"] == 0 && $row["delivery_name"]==""){
        if($row["od_cancel_date"]==""){//취소 시키기
            //결제 취소 업데이트 및 환불 || 배송은 = 상품건 물건인지 파악필요?
            $API_URL = "https://pg.mainpay.co.kr/csStdPayment/";

            $sql = "select *,p.mb_id as pd_mb_id,p.pd_id as pd_id from `product` as p left join `order` as o on p.pd_id = o.pd_id where o.od_id = '{$row["od_id"]}'";
            $pro = sql_fetch($sql);
            if($pro["pd_type"]==1){
                $od_price = $pro["od_price"];
            }else{
                if($pro["od_step"]==1){
                    $od_price = $pro["od_price"];
                }else if($pro["od_step"]==2 && $pro["od_fin_status"]==1){
                    $od_price = $pro["od_step1_price"]+$pro["od_step2_price"];
                }
            }
            if($pro["od_pay_type"]==1) {
                $sql = "select * from `order_payment_card_info` where pay_oid = '{$pro["pay_oid"]}'";
            }else if($pro["od_pay_type"]==3){
                $sql = "select * from `order_payment_account_info` where pay_oid = '{$pro["pay_oid"]}'";
            }
            $odType = sql_fetch($sql);
            // 결제 수단 공통
            $paymentType = $pro["od_pay_type"];							// 결제수단 [ 1 : 카드, 3 : 계좌이체, 4 : 휴대폰결제]
            $version = "3.3";							// 버전
            $mbrId = "103108";							// 가맹점아이디
            //$mbrId = "100011";							// 가맹점아이디
            $mbrName = "디자인율";						// 가맹점명
            $oid = $pro["pay_oid"];						// 가맹점주문번호
            $buyerName = $pro["od_name"];							// 구매자명
            $buyerMobile = $pro["od_tel"];						// 구매자전화번호
            $productName = $pro["pd_tag"];						// 상품명
            $salesPrice = $row["od_price"];							// 결제금액

            //신용카드 사용 변수
            $cardTradeNo = $odType["cardTradeNo"];						// 카드거래번호
            $cardApprovDate = date("ymd",strtotime($odType["cardApprovDate"]));						// 카드승인일
            $payType = $odType["payType"];							// 결제타입[ISP/3D]

            // 계좌이체 사용 변수
            $accountTradeNo = $odType["accountTradeNo"];							// 계좌이체거래번호
            $accountApprov = $odType["accountApprov"];                                                    // 계좌이체승인번호
            $accountApprovDate = date("ymd",strtotime($odType["accountApprovDate"]));                                                // 계좌이체승인일

            // 휴대폰 사용 변수
            $mobileTradeNo = "";                                                    // 휴대폰결제거래번호
            $mobileApprovDate = "";                                                 // 휴대폰결제승인일

            if($pro["od_pay_type"] == "1"){
                $fields = array(
                    'mbrId' => $mbrId,
                    'mbrName' => $mbrName,
                    'cardTradeNo' => $cardTradeNo,
                    'cardApprovDate' => $cardApprovDate,
                    'salesPrice' => $salesPrice,
                    'buyerName' => $buyerName,
                    'buyerMobile' => $buyerMobile,
                    'productName' => $productName,
                    'payType' => $payType,
                    'version' => $version,
                    'oid' => $oid
                );
                $API_URL = $API_URL . "cardCancel.do";
            }else if($pro["od_pay_type"] == "3"){
                $fields = array(
                    'version' => $version,
                    'mbrId' => $mbrId,
                    'mbrName' => $mbrName,
                    'oid' => $oid,
                    'accountTradeNo' => $accountTradeNo,
                    'accountApprovDate' => $accountApprovDate,
                    'buyerName' => $buyerName,
                    'buyerMobile' => $buyerMobile,
                    'productName' => $productName,
                    'salesPrice' => $salesPrice
                );
                $API_URL = $API_URL . "cashCancel.do";
            }
            $result = httpPost($API_URL, $fields);
            $obj = json_decode($result);

            if(($obj->{'resultCode'}) == "0000" || ($obj->{'resultCode'}) == "00"){
                /*echo("결과코드 = [" . $obj->{'resultCode'} . "]<br>");
                echo("결과메세지 = [" . $obj->{'resultMsg'} . "]<br>");
                echo("가맹점 아이디 = [" . $obj->{'mbrId'} . "]<br>");
                echo("취소일 = [" . $obj->{'cancelDate'} . "]");*/
                $sql = "update `order` set od_cancel_status = 2, od_cancel_date=now(), od_cancel_time = now() where od_id = '{$od_id}'";
                sql_query($sql);
                //todo:판매자에게 결제 취소 알림 및 물건일 경우 상품 판매중으로 변경
                if($pro["pd_type"]==1){
                    $sql = "update `product` set pd_status = 0 where pd_id = '{$pro["pd_id"]}'";
                    sql_query($sql);
                }
                $img = "";
                if ($od["pd_images"]) {
                    $imgs = explode(",", $od["pd_images"]);
                    $img = G5_DATA_URL . "/product/" . $imgs[0];
                }
                $mb = get_member($pro["pd_mb_id"]);
                if($mb["regid"]) {
                    send_FCM($mb["regid"], "48 결제 완료 알림", $pro["pd_tag"] . "의 배송지연으로 결제가 취소 되었습니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pro["pd_type"], 'fcm_buy_channel', '구매일림', $pro["mb_id"], $pro['pd_id'], $img);
                }
                $mb = get_member($pro["mb_id"]);
                if($mb["regid"]) {
                    send_FCM($mb["regid"], "48 결제 완료 알림", $pro["pd_tag"] . "의 배송지연으로 결제가 취소 되었습니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=' . $pro["pd_type"], 'fcm_buy_channel', '구매일림', $pro["mb_id"], $pro['pd_id'], $img);
                }
            }else{
                echo("결과코드b = [" . $obj->{'resultCode'} . "]<br>");
                echo("결과메세지 = [" . $obj->{'resultMsg'} . "]<br>");
            }

            /*$sql = "select * from `product` where pd_id = '{$row["pd_id"]}' ";
            $pd = sql_fetch($sql);
            if($pd["pd_type"]==1){//물건만 게시글 업데이트
                $sql = "update `product` set pd_status = 0 where pd_id = '{$row["pd_id"]}' ";
                sql_query($sql);
            }*/

        }
    }else if($expired_date < $today && $row["delivery_extend"] == 2 && $row["delivery_name"]==""){//연장했음
        $expired_date = date("Y-m-d",strtotime("+ 3 day",strtotime($expired_date)));//결제 완료일에서 3일에서 연장 7일추가
        if($today == $expired_date){//연장했는데 기간이 오늘인경우
            //판매자 정보 가져오기
            $sql = "select *,m.mb_id as mb_id from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where p.pd_id = '{$row["pd_id"]}' ";
            $pd = sql_fetch($sql);
            $img = "";
            if($pd["pd_images"]) {
                $imgs = explode(",",$pd["pd_images"]);
                $img = G5_DATA_URL."/product/".$imgs[0];
            }
            if($pd["regid"]){//기기정보가 있으면 푸시
                send_FCM($pd["regid"],"운송장입력안내",$pd["pd_name"]."의 운송장정보를 입력해주세요. 미입력시 1일이내에 자동 환불 처리가 됩니다.", G5_MOBILE_URL."/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=".$pd["pd_type"],"fcm_buy_channel","구매일림",$pd["mb_id"],$pd["pd_id"],$img);
            }
        }else if($today > $expired_date){
            //결제 취소 업데이트 및 환불
            //todo 결제 취소건 적용
            $API_URL = "https://pg.mainpay.co.kr/csStdPayment/";

            $sql = "select *,p.mb_id as pd_mb_id,p.pd_id as pd_id from `product` as p left join `order` as o on p.pd_id = o.pd_id where o.od_id = '{$row["od_id"]}'";
            $pro = sql_fetch($sql);
            if($pro["pd_type"]==1){
                $od_price = $pro["od_price"];
            }else{
                if($pro["od_step"]==1){
                    $od_price = $pro["od_price"];
                }else if($pro["od_step"]==2 && $pro["od_fin_status"]==1){
                    $od_price = $pro["od_step1_price"]+$pro["od_step2_price"];
                }
            }
            if($pro["od_pay_type"]==1) {
                $sql = "select * from `order_payment_card_info` where pay_oid = '{$pro["pay_oid"]}'";
            }else if($pro["od_pay_type"]==3){
                $sql = "select * from `order_payment_account_info` where pay_oid = '{$pro["pay_oid"]}'";
            }
            $odType = sql_fetch($sql);
            // 결제 수단 공통
            $paymentType = $pro["od_pay_type"];							// 결제수단 [ 1 : 카드, 3 : 계좌이체, 4 : 휴대폰결제]
            $version = "3.3";							// 버전
            $mbrId = "103108";							// 가맹점아이디
            //$mbrId = "100011";							// 가맹점아이디
            $mbrName = "디자인율";						// 가맹점명
            $oid = $pro["pay_oid"];						// 가맹점주문번호
            $buyerName = $pro["od_name"];							// 구매자명
            $buyerMobile = $pro["od_tel"];						// 구매자전화번호
            $productName = $pro["pd_tag"];						// 상품명
            $salesPrice = $row["od_price"];							// 결제금액

            //신용카드 사용 변수
            $cardTradeNo = $odType["cardTradeNo"];						// 카드거래번호
            $cardApprovDate = date("ymd",strtotime($odType["cardApprovDate"]));						// 카드승인일
            $payType = $odType["payType"];							// 결제타입[ISP/3D]

            // 계좌이체 사용 변수
            $accountTradeNo = $odType["accountTradeNo"];							// 계좌이체거래번호
            $accountApprov = $odType["accountApprov"];                                                    // 계좌이체승인번호
            $accountApprovDate = date("ymd",strtotime($odType["accountApprovDate"]));                                                // 계좌이체승인일

            // 휴대폰 사용 변수
            $mobileTradeNo = "";                                                    // 휴대폰결제거래번호
            $mobileApprovDate = "";                                                 // 휴대폰결제승인일

            if($pro["od_pay_type"] == "1"){
                $fields = array(
                    'mbrId' => $mbrId,
                    'mbrName' => $mbrName,
                    'cardTradeNo' => $cardTradeNo,
                    'cardApprovDate' => $cardApprovDate,
                    'salesPrice' => $salesPrice,
                    'buyerName' => $buyerName,
                    'buyerMobile' => $buyerMobile,
                    'productName' => $productName,
                    'payType' => $payType,
                    'version' => $version,
                    'oid' => $oid
                );
                $API_URL = $API_URL . "cardCancel.do";
            }else if($pro["od_pay_type"] == "3"){
                $fields = array(
                    'version' => $version,
                    'mbrId' => $mbrId,
                    'mbrName' => $mbrName,
                    'oid' => $oid,
                    'accountTradeNo' => $accountTradeNo,
                    'accountApprovDate' => $accountApprovDate,
                    'buyerName' => $buyerName,
                    'buyerMobile' => $buyerMobile,
                    'productName' => $productName,
                    'salesPrice' => $salesPrice
                );
                $API_URL = $API_URL . "cashCancel.do";
            }
            $result = httpPost($API_URL, $fields);
            $obj = json_decode($result);
            if(($obj->{'resultCode'}) == "0000" || ($obj->{'resultCode'}) == "00"){
                /*echo("결과코드 = [" . $obj->{'resultCode'} . "]<br>");
                echo("결과메세지 = [" . $obj->{'resultMsg'} . "]<br>");
                echo("가맹점 아이디 = [" . $obj->{'mbrId'} . "]<br>");
                echo("취소일 = [" . $obj->{'cancelDate'} . "]");*/
                $sql = "update `order` set od_cancel_status = 2, od_cancel_date=now(), od_cancel_time = now() where od_id = '{$od_id}'";
                sql_query($sql);
                //todo:판매자에게 결제 취소 알림 및 물건일 경우 상품 판매중으로 변경
                if($pro["pd_type"]==1){
                    $sql = "update `product` set pd_status = 0 where pd_id = '{$pro["pd_id"]}'";
                    sql_query($sql);
                }
                $img = "";
                if ($od["pd_images"]) {
                    $imgs = explode(",", $od["pd_images"]);
                    $img = G5_DATA_URL . "/product/" . $imgs[0];
                }
                $mb = get_member($pro["pd_mb_id"]);
                if($mb["regid"]) {
                    send_FCM($mb["regid"], "48 결제 완료 알림", $pro["pd_tag"] . "의 배송지연으로 결제가 취소 되었습니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pro["pd_type"], 'fcm_buy_channel', '구매일림', $pro["mb_id"], $pro['pd_id'], $img);
                }
                $mb = get_member($pro["mb_id"]);
                if($mb["regid"]) {
                    send_FCM($mb["regid"], "48 결제 완료 알림", $pro["pd_tag"] . "의 배송지연으로 결제가 취소 되었습니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=' . $pro["pd_type"], 'fcm_buy_channel', '구매일림', $pro["mb_id"], $pro['pd_id'], $img);
                }
            }else{
                echo("결과코드 = [" . $obj->{'resultCode'} . "]<br>");
                echo("결과메세지 = [" . $obj->{'resultMsg'} . "]<br>");
            }

            /*sql_query("update `order` set od_cancel_status = 2, od_cancel_date = now(), od_cancel_time = now(),");

            $sql = "select * from `product` where pd_id = '{$row["pd_id"]}' ";
            $pd = sql_fetch($sql);
            if($pd["pd_type"]==1){//물건만 게시글 업데이트
                $sql = "update `product` set pd_status = 0 where pd_id = '{$row["pd_id"]}' ";
                sql_query($sql);
            }*/
        }
    }
}

function httpPost($url,$params){
    $postData = '';

    foreach($params as $k => $v) {
        $postData .= $k . '='.$v.'&';
    }
    $postData = rtrim($postData, '&');

    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, count($postData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $output=curl_exec($ch);

    curl_close($ch);

    return $output;
}
?>