<?php
include_once("../../../../common.php");
	/*hashValue(서명값) 생성 예시 */
	//test
	$url = "https://dev-relay.mainpay.co.kr/v1/api/payments/payment/payat/trans";

	$mbrNo = "151001";
	$amount = 1000;
	$apiKey = "U1FVQVJFLTE1MTEyNDIwMTkwMjEzMTMxMjQzNjY0OTAx";
	$timestamp = date("Ymdhis");
	$mbrRefNo = $timestamp.GenerateString(4);;

	$cardNo = $_REQUEST["od_card_num"];

	$expd = $_REQUEST["od_expd"];

	$taxAmt = "0";

	$goodsName = "테스트";

	$charset = "utf-8";

	$clienType = urlencode("square");

	$currencyCode = "WON";

	$name = "테스트";

	$email = $member["mb_id"];

	$tel = $_REQUEST["tel1"].$_REQUEST["tel2"].$_REQUEST["tel3"];

	$installment = "0";

	$message = $mbrNo."|".$mbrRefNo."|".$amount."|".$apiKey."|".$timestamp;
	$signature = hash("sha256", $message);


	$furl = $url."?mbrNo=".$mbrNo."&mbrRefNo=".$mbrRefNo."&cardNo=".$cardNo."&expd=".$expd."&amount=".$amount."&taxAmt=".$taxAmt."&goodsName=".$goodsName."&charset=".$charset."&clientType=".$clienType."&signature=".$signature."&installment=".$installment."&customerName=".$name."&customerEmail=".$email."&customerTelNo=".$tel."&currencyCode=".$currencyCode."&timestamp=".$timestamp;

    $headers = array(
        'Authorization: key='.$apiKey,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    // Set the URL, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $furl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    // Execute post
    $result = curl_exec($ch);
    // Close connection
    curl_close($ch);

    $data = json_decode($result);

    print_r2($data);

    /*if($result["resultCode"]==200){

    }else{

    }*/

// 랜덤 함수(입력 길이에 맞게 아래 문자들중에 리턴)
function GenerateString($length)
{
    $characters  = "0123456789";
    $characters .= "abcdefghijklmnopqrstuvwxyz";
    $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $characters .= "_";

    $string_generated = "";

    $nmr_loops = $length;
    while ($nmr_loops--)
    {
        $string_generated .= $characters[mt_rand(0, strlen($characters))];
    }

    return $string_generated;
}

?>