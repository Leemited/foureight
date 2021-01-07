<?php
include_once ("../../../../common.php");
include_once (G5_PATH."/head.sub.php");
header('Content-Type: text/html; charset=utf-8');
require(G5_PATH.'/mobile/page/mypage/newpay/utils.php');                // 유틸리티 포함
$logPath = "./mobile/page/mypage/newpay/app.log";         //디버그 로그위치 (리눅스)
	//$logPath = /home/app.log"; //디버그 로그위치 (리눅스)

/********************************************************************************
 * 인증이 완료될 경우 PG사에서 호출하는 페이지 입니다.
 * PG로 부터 전달 받은 인증값을 받아 PG로 승인요청을 합니다.
 ********************************************************************************/
$od_id = $_SESSION['od_id'];
$sql = "select * from `order` where od_id = '{$od_id}'";
$order = sql_fetch($sql);

$aid = $_REQUEST["aid"];
$authToken = $_REQUEST["authToken"];;
$merchantData = $_REQUEST["merchantData"];;
$receiveParam = "## 인증결과수신 ## aid:".$aid.", authToken:".$authToken;
//pintLog("RECEIVE-PARAM: ".$receiveParam, $logPath);
//echo $receiveParam; // 실운영시 제거

/*_1_start 에서 저장한 session 값 조회*/
//session_start();
$parameters = $_SESSION['readyParameters'];
$apiKey = $_SESSION['apiKey'];
if($order["od_step"]==1 && $order["od_pay_status"]==1){//계약금은 능력만
    if ($order["od_pay_type"] == 1) {//카드
        $price = ceil($order["od_price"] + ($order["od_price"] * 0.035));
    } else {//계좌이체
        $price = ceil($order["od_price"] + ($order["od_price"] * 0.02));
    }
}else {
    if($order["od_pd_type"]==1) {
        if ($order["od_pay_type"] == 1) {//카드
            $price = ceil($order["od_price"] + ($order["od_price"] * 0.035));
        } else {//계좌이체
            $price = ceil($order["od_price"] + ($order["od_price"] * 0.02));
        }
    }else{
        if($order["od_price2"]) {
            if ($order["od_pay_type"] == 1) {//카드
                $price = ceil($order["od_price2"] + ($order["od_price2"] * 0.035));
            } else {//계좌이체
                $price = ceil($order["od_price2"] + ($order["od_price2"] * 0.02));
            }
        }else{
            if ($order["od_pay_type"] == 1) {//카드
                $price = ceil($order["od_price"] + ($order["od_price"] * 0.035));
            } else {//계좌이체
                $price = ceil($order["od_price"] + ($order["od_price"] * 0.02));
            }
        }
    }
}

$amount = (int) $price; //위변조 방지를 위해 DB등에서 가져온 값을 세팅합니다.

$API_BASE = $_SESSION['API_BASE'];

$mbrNo = $parameters["mbrNo"];
$mbrRefNo = $parameters["mbrRefNo"];

/* timestamp max 20byte*/
$timestamp = makeTimestamp();
/* signature 64byte*/
$signature = makeSignature($mbrNo,$mbrRefNo,$amount,$apiKey,$timestamp);

$parameters["aid"] = $aid;
$parameters["authToken"] = $authToken;
$parameters["amount"] = $amount;
$parameters["timestamp"] = $timestamp;
$parameters["signature"] = $signature;

/********************************************************************************
* 승인요청 API 호출
*********************************************************************************/
$PAY_API_URL = $API_BASE."/v1/payment/pay";
$result = "";
$errorMessage = "";

try{
    //pintLog("PAY-API: ".$PAY_API_URL, $logPath);
    //pintLog("PARAM: ".print_r($parameters, TRUE), $logPath);
    $result = httpPost($PAY_API_URL, $parameters);
} catch(Exception $e) {
    $errorMessage = "승인요청API 호출실패: " . $e;
    //pintLog($errorMessage, $logPath);

    /*********************************************************************************
    * 망취소 처리(승인API 호출 도중 응답수신에 실패한 경우)
    *********************************************************************************/
    $NET_CANCEL_URL = $API_BASE."/v1/payment/net-cancel";
    $result = httpPost($NET_CANCEL_URL, $parameters);
    return;
}
//echo("<br>## 승인요청API 호출 결과 : <br>" . $result);
//pintLog("RESPONSE: ".$result, $logPath);

$obj = json_decode($result);
$resultCode = $obj->{'resultCode'};
$resultMessage = $obj->{'resultMessage'};
$data = $obj->{'data'};
/* 추가 항목은 연동매뉴얼 참조*/
$refNo = "";      // 거래번호
$tranDate = "";	  // 거래일자

/*********************************************************************************
* 승인결과 처리 (결과에 따라 상점 DB처리 및 화면 전환 처리)
*********************************************************************************/
if($resultCode != "200"){
    /*호출실패*/
    $errorMessage = "<br>## 승인요청API 호출 결과: resultCode:".$resultCode . ", resultMessage". $resultMessage;
    //echo $errorMessage; // 실운영시 제거
    alert("$resultMessage",G5_MOBILE_URL."/page/mypage/orders.php?od_id=".$od_id);
    return;
} else {
    /*승인요청API 호출 성공*/
    //공통값
    $refNo = $data->{'refNo'};
    $tranDate = $data->{'tranDate'};
    $tranTime = $data->{'tranTime'};
    $payType = $data->{'payType'};
    $mbrRefNo = $data->{'mbrRefNo'};

    switch ($_SESSION["paymethod"]){
        case "CARD":
            $paymethod = 1;
            break;
        case "ACCT":
            $paymethod = 3;
            break;
    }
    if($paymethod==1){
        $installment = $data->{'installment'};
        $applNo = $data->{'applNo'};
        $cardNo = $data->{'cardNo'};
        $issueCompanyNo = $data->{'issueCompanyNo'};
        $acqCompanyNo = $data->{'acqCompanyNo'};

        $card_sql = " , installment = '{$installment}' , applNo = '{$applNo}', cardNo = '{$cardNo}', issueCompanyNo = '{$issueCompanyNo}', acqCompanyNo = '{$acqCompanyNo}' ";
    }
    if($paymethod==3){
        $bankCode = $data -> {'bankCode'};
        $bankName = $data -> {'bankName'};

        $card_sql = " , bankCode = '{$bankCode}', bankName= '{$bankName}'";
    }

    $sql3 = "insert into `order_pay_info` set refNo = '{$refNo}', aid = '{$aid}', amount = '{$amount}', tranDate = '{$tranDate}', tranTime = '{$tranTime}', od_id='{$od_id}', payType = '{$payType}', paymethod = '{$paymethod}', mbrRefNo = '{$mbrRefNo}',status = 0 {$card_sql}";
    sql_query($sql3);

    /*== 가맹점 DB 주문처리 ==*/
    $sql = "select *,o.mb_id as mb_id,p.mb_id as pd_mb_id,p.pd_id as pd_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$od_id}'";
    $pro = sql_fetch($sql);
    $app_mb_id = $pro["pd_mb_id"];

    if($pro["pd_type"]==2 && $pro["od_step"]==1){ //2차결제
        $sql = "update `order` set 
                  pay_oid2 = '{$mbrRefNo}',
                  od_pay_type2='{$paymethod}',
                  od_step = 2,
                  od_step2_price = '{$amount}',
                  od_fin_status = 1,
                  od_fin_confirm = 1,
                  od_fin_datetime = now()
                  where od_id = '{$od_id}'
                ";
        sql_query($sql);

        $pd_mb = get_member($pro["pd_mb_id"]);
        //판매자에게 알림
        if ($pd_mb["regid"]) {
            $img = "";
            if ($pro["pd_images"]) {
                $imgs = explode(",", $pro["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }
            send_FCM($pd_mb["regid"], "48 결제 완료 알림", cut_str($pro["pd_tag"],10,"...") . "의 잔금결제가 완료 되었습니다.\r\n대금 정산은 결제완후 4일안에 자동 입금됩니다.\r\n정산계좌가 등록되지 않은 경우 정산이 지연될수 있습니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pro["pd_type"], 'fcm_buy_channel', '구매일림', $pd_mb["mb_id"], $pro['pd_id'], $img);
        }
    }
    if($pro["od_step"]==0){ //1차결제
        //주문상태 업데이트
        $sql = "update `order` set 
                  od_pay_type='{$paymethod}',
                  od_pay_status='1', 
                  od_date=now(),
                  od_step=1,
                  od_step1_price='{$amount}',
                  pay_oid = '{$mbrRefNo}'
                  where od_id = '{$od_id}'
                ";

        sql_query($sql);

        if ($pro["od_pd_type"] == 1) {
            //물건일 경우 판매 완료 처리
            $sql = "update `product` set pd_status = 4 where pd_id = '{$pro["pd_id"]}'";
            sql_query($sql);

        }
        $pd_mb = get_member($pro["pd_mb_id"]);
        //판매자에게 알림
        if ($pd_mb["regid"]) {
            $img = "";
            if ($pro["pd_images"]) {
                $imgs = explode(",", $pro["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }
            send_FCM($pd_mb["regid"], "48 결제 완료 알림", cut_str($pro["pd_tag"],10,"...") . "의 결제가 완료 되었습니다.\r\n안전한 거래를 위해 결제 상태를 확인 바랍니다.\r\n문제가 있는경우 관리자에게 문의 바랍니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pro["pd_type"], 'fcm_buy_channel', '구매일림', $pd_mb["mb_id"], $pro['pd_id'], $img);
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, user-scalable=no">
<script src="https://api-std.mainpay.co.kr/js/mainpay.pc-1.0.js"></script>
</head>
<body>
<script>
/* 결제 완료 페이지 호출 */
var resultCode = "<?php echo $resultCode ?>";
var resultMessage = "<?php echo $resultMessage ?>";

/* 결제처리 성공 유무에 따른 화면 전환 */
if(resultCode == "200") {
    location.href = g5_url+"/mobile/page/mypage/newpay/_4_complete.php?od_id=<?php echo $od_id;?>";
}
</script> 
</body>
</html>