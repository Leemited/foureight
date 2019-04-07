<?php
	$API_URL = "https://testpg.mainpay.co.kr/csStdPayment/";				// 테스트


	// 결제 수단 공통
	$paymentType = "1";							// 결제수단 [ 1 : 카드, 3 : 계좌이체, 4 : 휴대폰결제] 
	$version = "3.1";							// 버전
	$mbrId = "100011";							// 가맹점아이디
	$mbrName = "테스트 상점";						// 가맹점명
	$oid = "201707191059470dn";						// 가맹점주문번호
	$buyerName = "홍길동";							// 구매자명
	$buyerMobile = "01012345678";						// 구매자전화번호
	$productName = "테스트상품";						// 상품명
	$salesPrice = 1000;							// 결제금액

	//신용카드 사용 변수
	$cardTradeNo = "0719C0399043";						// 카드거래번호
	$cardApprovDate = "170719";						// 카드승인일
	$payType = "ISP";							// 결제타입[ISP/3D]

	// 계좌이체 사용 변수
	$accountTradeNo = "";							// 계좌이체거래번호
	$accountApprov = "";                                                    // 계좌이체승인번호 
        $accountApprovDate = "";                                                // 계좌이체승인일 

        // 휴대폰 사용 변수 
        $mobileTradeNo = "";                                                    // 휴대폰결제거래번호 
        $mobileApprovDate = "";                                                 // 휴대폰결제승인일

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

	
	if($paymentType == "1"){
		$API_URL = $API_URL . "cardCancel.do";
	}else if($paymentType == "3"){
		$API_URL = $API_URL . "cashCancel.do";
	}else if($paymentType == "4"){
		$API_URL = $API_URL . "mobileCancel.do";
	}

	$result = httpPost($API_URL, $fields);
	$obj = json_decode($result);

	if(($obj->{'resultCode'}) == "0000" || ($obj->{'resultCode'}) == "00"){ 
              echo("결과코드 = [" . $obj->{'resultCode'} . "]<br>"); 
              echo("결과메세지 = [" . $obj->{'resultMsg'} . "]<br>"); 
              echo("가맹점 아이디 = [" . $obj->{'mbrId'} . "]<br>"); 
              echo("취소일 = [" . $obj->{'cancelDate'} . "]"); 
        }else{ 
              echo("결과코드 = [" . $obj->{'resultCode'} . "]<br>"); 
              echo("결과메세지 = [" . $obj->{'resultMsg'} . "]"); 
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
