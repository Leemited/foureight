<?php
    include_once ("../../../../common.php");
	/*hashValue(서명값) 생성 예시 */	
	$mbrNo = "5414400091";
	$mbrRefNo = "48Q".date("Ymdhis");
	$amount = 1000;
	$apiKey = "U1FVQVJFLTE1MDAwMTIwMTYwNjEzMTU0NzExOTI3ODkz";
	$timestamp = date("Ymdhis");

	$carNo = "";
	$expd = "";

    $amount = "";
    $taxAmt = "";

    $goodsName= "";
	
	
	$message = $mbrNo."|".$mbrRefNo."|".$amount."|".$apiKey."|".$timestamp;
	$signature = hash("sha256", $message);

    $url = "https://dev-relay.mainpay.co.kr/v1/api/payments/payment/payat/trans";

    $url = $url."?mbrNo=".$mbrNo."&mbrRefNo=".$mbrRefNo."&cardNo=9445421189496070&expd=0323&amount=1100&taxAmt=100&goodsName=테스트&charset=utf-8&timestamp=".$timestamp."&signature=".$signature."&clientType=square&currencyCode=WON";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

    $data = curl_exec($ch);
    if (curl_error($ch))
    {
        exit('CURL Error('.curl_errno( $ch ).') '.

            curl_error($ch));
    }
    curl_close($ch);

    print_r($data);
?>

