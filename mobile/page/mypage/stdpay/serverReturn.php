<?php
include_once ("../../../../common.php");
// 공통
$mbrId=$_REQUEST["mbrId"];                                      // 가맹점아이디
$rstCode= $_REQUEST["rstCode"];                                 // 결과코드
$rstMsg= $_REQUEST["rstMsg"];                                   // 결과 메세지
$oid= $_REQUEST["oid"];                                         // 가맹점 주문번호
$payKind= $_REQUEST["payKind"];                                 // 결제종류[1:카드, 2:가상계좌, 3:계좌이체, 4:휴대폰]
$salesPrice=$_REQUEST["salesPrice"];                            // 결제 금액

// 신용카드
if($payKind == "1"){
	$payType= $_REQUEST["payType"];                         // 결제 타입
	$authType= $_REQUEST["authType"];                       // 인증 타입
	$cardTradeNo= $_REQUEST["cardTradeNo"];                 // 카드 거래번호
	$cardApprovDate= $_REQUEST["cardApprovDate"];           // 카드 승인일
	$cardApprovTime= $_REQUEST["cardApprovTime"];           // 카드 승인시각
	$cardName= $_REQUEST["cardName"];                       // 카드명
	$cardCode= $_REQUEST["cardCode"];                       // 카드코드
	$installNo= $_REQUEST["installNo"];                     // 할부개월

// 가상계좌
}else if($payKind == "2"){
	$vAccountTradeNo= $_REQUEST["vAccountTradeNo"];         // 가상계좌 거래번호
	$vAccount= $_REQUEST["vAccount"];			// 가상계좌번호
	$vCriticalDate= $_REQUEST["vCriticalDate"];		// 입금마감일
	$vAccountBankName= $_REQUEST["vAccountBankName"];	// 거래은행명
	$vAccountBankCode= $_REQUEST["vAccountBankCode"];	// 거래은행코드

// 계좌이체
}else if($payKind == "3"){
	$accountTradeNo = $_REQUEST["accountTradeNo"];		// 계좌이체 거래번호
	//$accountApprov = $_REQUEST["accountApprov"];		// 계좌이체 승인번호
	$ApprovDate = $_REQUEST["ApprovDate"];	// 계좌이체 승인일
	$accountApprovTime = $_REQUEST["accountApprovTime"];	// 계좌이체 승인시각
	$accountBankName= $_REQUEST["accountBankName"];		// 거래은행명
	$accountBankCode= $_REQUEST["accountBankCode"];		// 거래은행코드

// 휴대폰
}else if($payKind == "4"){
	$mobileTradeNo= $_REQUEST["mobileTradeNo"];		// 휴대폰 거래번호
	$mobileApprovDate= $_REQUEST["mobileApprovDate"];	// 휴대폰 승인일
	$mobileApprovTime= $_REQUEST["mobileApprovTime"];	// 휴대폰 승인시각
	$BILLTYPE= $_REQUEST["BILLTYPE"];			// 월자동결제 여부

	// 휴대폰 월자동 결제 시
	if($BILLTYPE == "21"){
		$BATCH_KEY= $_REQUEST["BATCH_KEY"];
	}
}

if($rstCode == "0000" || $rstCode =="00") {
	// 결제 성공, 주문관련 DB처리를 합니다.
	$result_ok = '{"rescode":"00"}';
	echo $result_ok;
}else{
	// // 결제실패, 해당 처리를 합니다.
	 $result_fail = '{"rescode":"99"}';
	echo $result_fail;
}

?>
