<?php
include_once ("../../../../common.php");

// 가맹점 웹서버 호스트
$host = "http://mave01.cafe24.com/mobile/page/mypage/stdpay/";
$od_tel = $tel1."-".$tel2."-".$tel3;

// ****************************************** 필수 파리미터 셋팅[가맹점 상황에 맞게 수정해서 사용하세요]******************************************
// 현재시간 가져오기(timestamp)
date_default_timezone_set("Asia/Seoul");
$timestamp = date('YmdHis');
// 버전 정보 수정 금지
$version = "3.1";
// 가맹점 번호
$mbrId = "151124";
// 결제 금액
$salesPrice = "{$od_price}";
// 가맹점 주문번호(가맹점에서 실제 관리하는 주문번호를 사용)
$oid = $timestamp.GenerateString(4);
// hashValue 생성
$sign = hash("sha256", $mbrId.'|'.$salesPrice.'|'.$oid.'|'.$timestamp,false);
$hashValue = $timestamp.$sign;

// 가맹점명
$mbrName = "디자인율";
// 가맹점 사업자 번호
$bizNo = "5414400091";
// 구매자 명
$buyerName = $od_name;
// 구매자 휴대폰 번호
$buyerMobile = $od_tel;
// 구매자 Email
$buyerEmail = $member["mb_id"];
// 상품명
$productName = $od_item_name;
// 휴대폰 결제 사용 시 아래 값 사용
$CPCODE = "";


//$sql = "";

// ****************************************** 필수 파리미터 셋팅 끝 [가맹점 상황에 맞게 수정해서 사용하세요]*************************************

//**************************************************************************************************************************
// 3. 결제결과 수신 URL
//  returnUrl은 PG->가맹점서버로 결제결과를 전달
//  returnUrl결과가 성공일 경우 가맹점 DB에 주문 처리
// *** 중요 ***//
// 가맹점에서 returnUrl의 Response를 {"rescode":"00"} 의 Json형태로 응답해야 함
// 상기 형태가 아니거나, value가 "00"이 아닐 경우 미매칭 거래로 간주 취소처리 됨
//**************************************************************************************************************************

$returnUrl = $host."serverReturn.php";
$callbackUrl = $host."clientCallback.php";
$cancelUrl = $host."cancel.php";

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
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style type="text/css">
        body { background-color: #efefef;}
        body, tr, td {font-size:9pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
        table, img {border:none}
    </style>
    <title>표준결제창 테스트 페이지</title>
    <!-- 테스트용 -->
    <script type="text/javascript" src="https://testpg.mainpay.co.kr/csStdPayment/resources/script/v1/c2StdPay.js"></script>
    <!-- 운영환경용 -->
    <!--<script type="text/javascript" src="https://pg.mainpay.co.kr/csStdPayment/resources/script/v1/c2StdPay.js" ></script>-->
</head>
<body  bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0>
<div style="padding:10px;width:100%;font-size:13px;color: #000000;background-color: #efefef;text-align: center">
    <strong>C`SQUARE 표준결제 결제요청 샘플  </strong>
</div>
<table width="650" border="0" cellspacing="0" cellpadding="0" style="padding:10px;display:block;" align="center">
    <tr>
        <td bgcolor="6095BC" align="center" >
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="padding:20px">
                <tr>
                    <td>
                        이 페이지는 표준결제창 개발을 위한 샘플 페이지 입니다..<br/>
                        <br/>
                        <font color="#336699"><strong>함께 제공되는 메뉴얼을 참조하여 개발하시기 바랍니다.</strong></font>

                        <br/><br/>
                    </td>
                </tr>
                <tr>
                    <td >
                        <button id="btn_pay" onclick="C2StdPay.pay()" style="padding:10px">결제요청</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="text-align:left;">

                                    <!-- ##########################-->
                                    <!-- #### 중요 : form 명칭 변경 금지 #####-->
                                    <!-- ##########################-->
                                    <form name="mallForm"  id="mallForm"  method="POST">
                                        <!-- 필수 -->
                                        <br/><b>***** 필 수 *****</b>
                                        <div style="border:2px #dddddd double;padding:10px;">
                                            <br/><b>version</b> :
                                            <br/><input  style="width:100%;" name="version" value="<?php echo $version?>">

                                            <br/><b>server</b> :
                                            [  0 : Test 서버, 1 : Real 서버]
                                            <br/><input  style="width:100%;" name="server" value="0" >

                                            <br/><b>mbrId</b> :
                                            <br/><input  style="width:100%;" name="mbrId" value="<?php echo $mbrId?>">

                                            <br/><b>mbrName</b> :
                                            <br/><input  style="width:100%;" name="mbrName" value="<?php echo $mbrName?>">

                                            <br/><b>payKind</b> : 결제수단 [  1 : 카드, 2 : 가상계좌, 3 : 계좌이체, 4 : 휴대폰결제]
                                            <br/><input  style="width:100%;" name="payKind" value="<?php echo $od_type;?>" >

                                            <br/><b>buyerName</b> :
                                            <br/><input  style="width:100%;" name="buyerName" value="<?php echo $buyerName?>" >

                                            <br/><b>buyerMobile</b> :
                                            <br/><input  style="width:100%;" name="buyerMobile" value="<?php echo $buyerMobile?>" >

                                            <br/><b>buyerEmail</b> :
                                            <br/><input  style="width:100%;" name="buyerEmail" value="<?php echo $buyerEmail?>" >

                                            <br/><b>productName</b> :
                                            <br/><input  style="width:100%;" name="productName" value="<?php echo $productName?>" >

                                            <br/><b>salesPrice</b> :
                                            <br/><input  style="width:100%;" name="salesPrice" value="<?php echo $salesPrice?>" >

                                            <br/><b>bizNo</b> :
                                            <br/><input  style="width:100%;" name="bizNo" value="<?php echo $bizNo?>" >

                                            <br/><b>oid</b> :
                                            <br/><input  style="width:100%;" name="oid" id="oid" value="<?php echo $oid?>" >

                                            <br/><b>returnType</b> :
                                            <br/><input  style="width:100%;" name="returnType" value="payment" > <!-- 고정 -->

                                            <br/><b>authType</b> :
                                            <br/><input  style="width:100%;" name="authType" value="auth" /> <!-- 고정 -->

                                            <br/><b>returnUrl</b> :
                                            <br/><input  style="width:100%;" name="returnUrl" value="<?php echo $returnUrl?>" >

                                            <br/><b>callbackUrl</b> :
                                            <br/><input  style="width:100%;" name="callbackUrl" value="<?php echo $callbackUrl?>" >

                                            <br/><b>hashValue</b> :
                                            <br/><input  style="width:100%;" name="hashValue" id="hashValue" value="<?php echo $hashValue?>" >

                                            <br/>
                                        </div>

                                        <br/><br/>
                                        <b>***** 옵션 *****</b>
                                        <div style="border:2px #dddddd double;padding:10px;">

                                            <br/><b>viewType</b> : 결제창 표시방법
                                            <br/>[popup|self] (default:popup,모바일은:self)
                                            <br/><input  style="width:100%;" name="viewType" value="self" >

                                            <br/><b>notiYn</b> :
                                            <br/><input  style="width:100%;" name="notiYn" value="N" >

                                            <br/><b>BILLTYPE</b> : 휴대폰 결제 타입 (00:단건, 21:월자동)
                                            <br/><input  style="width:100%;" name="BILLTYPE" value="00" >

                                            <br/><b>payType</b> : CSQ 모바일 웹 키인
                                            <br/><input  style="width:100%;" name="payType" value="CSQ" >
                                            <br/>

                                        </div>

                                        <br/><br/>
                                        <b>***** 씨스퀘어 전용 옵션 ******</b>
                                        <div style="border:2px #dddddd double;padding:10px;">

                                            <br/><b>host</b> :
                                            <br/><input  style="width:100%;" name="host" value="<?php echo $host?>" >
                                            <br/><br/>

                                            <b style="padding:300px"> </b>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

