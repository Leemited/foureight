<?php
include_once ("../../../../common.php");
//include_once (G5_PATH."/head.sub.php");
//header('Content-Type: application/json; charset=utf-8');
require(G5_PATH.'/mobile/page/mypage/newpay/utils.php');                // 유틸리티 포함
//$logPath = "c://app.log";            //디버그 로그위치 (windows)
//$logPath = /home/app.log";         //디버그 로그위치 (리눅스)

$sql = "select * from `order` as o left join `g5_member` as m on o.mb_id = m.mb_id where od_id = '{$od_id}' ";
$order = sql_fetch($sql);

$sql = "select * from `order_pay_info` where od_id = '{$od_id}' and mbrRefNo = '{$pay_oid}'";
$od = sql_fetch($sql);

$sql = "select * from `product` where pd_id = '{$order["pd_id"]}'";
$pd = sql_fetch($sql);

switch ($od["paymethod"]){
    case 1:
        $paymethod = "CARD";
        break;
    case 3:
        $paymethod = "ACCT";
        break;
    default :
        $paymethod = "CARD";
        break;
}

/*****************************************************************************************
 * CANCEL API URL  (결제 취소 URL)
 ******************************************************************************************
- API 호출 도메인
- ## 테스트 완료후 real 서비스용 URL로 변경  ##
- 리얼-URL : https://relay.mainpay.co.kr/v1/api/payments/payment/cancel
- 개발-URL : https://dev-relay.mainpay.co.kr/v1/api/payments/payment/cancel
 */

$CANCEL_API_URL = "https://relay.mainpay.co.kr/v1/api/payments/payment/cancel";

/*
  API KEY (비밀키)
 - 생성 : http://biz.mainpay.co.kr 고객지원>기술지원>암호화키관리
 - 가맹점번호(mbrNo) 생성시 함께 만들어지는 key (테스트 완료후 real 서비스용 발급필요) */
$apiKey = "U1FVQVJFLTEwMzEwODIwMTkwMjEzMTMwODQyMjAyNzYz"; // <===테스트용 API_KEY입니다. 100011


/*****************************************************************************************
 *	취소 요청 파라미터
 ******************************************************************************************/
$version = "1.0";
/* 가맹점 아이디(테스트 완료후 real 서비스용 발급필요)*/
$mbrNo = "103108"; //<===테스트용 가맹점아이디입니다.
/* 가맹점 주문번호 (가맹점 고유ID 대체가능) 6byte~20byte*/
$mbrRefNo = $pay_oid;
/* 원거래번호 (결제완료시에 수신한 값)*/
$orgRefNo = $od["refNo"];
/* 원거래일자(결제완료시에 수신한 값) YYMMDD */
$orgTranDate = $od["tranDate"];
/* 원거래 지불수단 (CARD:신용카드|VACCT:가상계좌|ACCT:계좌이체|HPP:휴대폰소액) */
//$paymethod = $paymethod;
/* 결제금액 */
$amount = $od["amount"];
/* 결제타입 (결제완로시에 받은 값) */
$payType = $od["payType"];
/* 망취소 유무(Y:망취소, N:일반취소) (주문번호를 이용한 망취소시에 사용) */
$isNetCancel = "N";
/* 고객명 특수문자 사용금지, URL인코딩 필수 max 30byte */
$customerName = urlencode($order["od_name"]);
/* 고객이메일 이메일포멧 체크 필수 max 50byte */
$customerEmail = $order["mb_id"];

/* timestamp max 20byte*/
$timestamp = makeTimestamp();
/* signature 64byte*/
$signature = makeSignature($mbrNo,$mbrRefNo,$amount,$apiKey,$timestamp);

$parameters = array(
    'version' => $version,
    'mbrNo' => $mbrNo,
    'mbrRefNo' => $mbrRefNo,
    'orgRefNo' => $orgRefNo,
    'orgTranDate' => $orgTranDate,
    'paymethod' => $paymethod,
    'amount' => $amount,
    'payType' => $payType,
    'isNetCancel' => $isNetCancel,
    'customerName' => $customerName,
    'customerEmail' => $customerEmail,
    'timestamp' => $timestamp,
    'signature' => $signature
);

/*****************************************************************************************
 * CANCEL API 호출
 *****************************************************************************************/
$result = "";
$errorMessage = "";
try{
    //pintLog("CANCEL-API: ".$CANCEL_API_URL, $logPath);
    //pintLog("PARAM: ".print_r($parameters, TRUE), $logPath);

    $result = httpPost($CANCEL_API_URL, $parameters);
} catch(Exception $e) {
    $errorMessage = "결제 취소 API 호출실패: " . $CANCEL_API_URL;
    //pintLog("ERROR: ".$errorMessage, $logPath);
    //throw new Exception($e);
    return;
}

//pintLog("RESPONSE: ".$result, $logPath);

$obj = json_decode($result);
$resultCode = $obj->{'resultCode'};
$resultMessage = $obj->{'resultMessage'};

if($resultCode = "200"){
    $data = $obj->{'data'};
    //주문건 취소
    $sql = "update `order_pay_info` set `status` = -1 where od_id = '{$od_id}' and mbrRefNo = '{$pay_oid}'";
    sql_query($sql);

    $sql = "update `order` set od_cancel_status = 2, od_cancel_date=now(), od_cancel_time = now() where od_id = '{$od_id}'";
    sql_query($sql);
    //todo:판매자에게 결제 취소 알림 및 물건일 경우 상품 판매중으로 변경
    if($pd["pd_type"]==1){
        $sql = "update `product` set pd_status = 0 where pd_id = '{$pd["pd_id"]}'";
        sql_query($sql);
    }
    $mb = get_member($order["mb_id"]);
    if($mb["regid"]) {
        $img = "";
        if ($pd["pd_images"]) {
            $imgs = explode(",", $pd["pd_images"]);
            $img = G5_DATA_URL . "/product/" . $imgs[0];
        }
        if($type=="cancel"){
            $msg = cut_str($pd["pd_tag"], 10, "...") . "의 결제가 취소 되었습니다.\r\n거래 대금 환불이 안된 경우는 관리자에게 문의바랍니다.\r\n영업일 2일이내에 구매자에게 환불 됩니다.";
            $link = G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pd["pd_type"];
        }else {
            if ($type == 1) {//판매자가 구매자에게
                $msg = cut_str($pd["pd_tag"], 10, "...") . "의 반품이 완료 되었습니다.\r\n거래 대금 환불이 안된 경우는 관리자에게 문의바랍니다.\r\n환불 완료건은 영업일 2일이내에 구매자에게 환불 됩니다.";
                $link = G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pd["pd_type"];
            } else {//판매자가 구매자에게
                $msg = cut_str($pd["pd_tag"], 10, "...") . "의 결제가 취소 되었습니다.\r\n거래 대금 환불이 안된 경우는 관리자에게 문의바랍니다.\r\n환불 완료건은 영업일 2일이내에 구매자에게 환불 됩니다.";
                $link = G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pd["pd_type"];
            }
        }
        if($mb["regid"]) {
            send_FCM($mb["regid"], "48 결제 취소 알림", $msg, $link, 'fcm_buy_channel', '구매일림', $mb["mb_id"], $pd['pd_id'], $img);
        }
    }

    alert("취소 되었습니다.");

    // 하단 JSON TYPE RESPONSE 참고하여 데이터 저장
}else{
    alert($resultMessage);
}

?>

