<?php
include_once ("../../../../common.php");
	$API_URL = "https://pg.mainpay.co.kr/csStdPayment/";

	$sql = "select *,p.mb_id as pd_mb_id,p.pd_id as pd_id,o.mb_id as mb_id from `product` as p left join `order` as o on p.pd_id = o.pd_id where o.od_id = '{$od_id}'";
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
	$salesPrice = $od_price;							// 결제금액

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
		$sql = "update `order` set od_cancel_status = 2, od_cancel_date=now(), od_cancel_time = now() where od_id = '{$od_id}'";
		sql_query($sql);
		//todo:판매자에게 결제 취소 알림 및 물건일 경우 상품 판매중으로 변경
		if($pro["pd_type"]==1){
			$sql = "update `product` set pd_status = 0 where pd_id = '{$pro["pd_id"]}'";
			sql_query($sql);
		}
		$mb = get_member($pro["mb_id"]);
		if($mb["regid"]) {
            $img = "";
            if ($od["pd_images"]) {
                $imgs = explode(",", $od["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }
            if($type=="refund"){//판매자가 구매자에게
            	//$title = "48 결제 취소 알림";
            	$msg = cut_str($pro["pd_tag"],10,"...") . "의 반품이 완료 되었습니다.\r\n거래 대금 환불이 안된 경우는 관리자에게 문의바랍니다.\r\n고객센터 > 1:1 문의를 이용바랍니다.";
                $link = G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pro["pd_type"];
			}else{//판매자가 구매자에게
                //$title = "48 결제 취소 알림";
                $msg = cut_str($pro["pd_tag"],10,"...") . "의 결제가 취소 되었습니다.\r\n거래 대금 환불이 안된 경우는 관리자에게 문의바랍니다.\r\n고객센터 > 1:1 문의를 이용바랍니다.";
				$link = G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pro["pd_type"];
			}
            send_FCM($mb["regid"], "48 결제 취소 알림", $msg, $link, 'fcm_buy_channel', '구매일림', $pro["mb_id"], $pro['pd_id'], $img);
        }

        alert("취소 되었습니다.");
	}else{
        $sql = "update `order` set od_cancel_status = 2, od_cancel_date=now(), od_cancel_time = now() where od_id = '{$od_id}'";
        sql_query($sql);
        //todo:판매자에게 결제 취소 알림 및 물건일 경우 상품 판매중으로 변경
        if($pro["pd_type"]==1){
            $sql = "update `product` set pd_status = 0 where pd_id = '{$pro["pd_id"]}'";
            sql_query($sql);
        }
        $mb = get_member($pro["mb_id"]);
        if($mb["regid"]) {
            $img = "";
            if ($od["pd_images"]) {
                $imgs = explode(",", $od["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }
            if($type=="refund"){//판매자가 구매자에게
                //$title = "48 결제 취소 알림";
                $msg = cut_str($pro["pd_tag"],10,"...") . "의 반품이 완료 되었습니다.\r\n거래 대금 환불이 안된 경우는 관리자에게 문의바랍니다.\r\n고객센터 > 1:1 문의를 이용바랍니다.";
                $link = G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pro["pd_type"];
            }else{//판매자가 구매자에게
                //$title = "48 결제 취소 알림";
                $msg = cut_str($pro["pd_tag"],10,"...") . "의 결제가 취소 되었습니다.\r\n거래 대금 환불이 안된 경우는 관리자에게 문의바랍니다.\r\n고객센터 > 1:1 문의를 이용바랍니다.";
                $link = G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pro["pd_type"];
            }
            send_FCM($mb["regid"], "48 결제 취소 알림", $msg, $link, 'fcm_buy_channel', '구매일림', $pro["mb_id"], $pro['pd_id'], $img);
        }

        alert("취소 되었습니다.");
        $resultCode = $obj->{'resultCode'};
        $resultMsg = $obj->{'resultMsg'};
        //오류에 대한 건 저장해놔야 할듯...
        $sql = "insert into `order_history` set resultCode = '{$resultCode}', resultMsg = '{$resultMsg}', pay_oid = '{$pro["pay_oid"]}', od_id = '{$row["od_id"]}'";
        sql_query($sql);
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
